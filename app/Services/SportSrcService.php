<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SportSrcService
{
    private string $baseUrl;
    private string $apiKey;
    private int $timeout;
    private int $retries;
    private int $retryDelay;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('sportsrc.base_url', 'https://api.sportsrc.org/v2/'), '/') . '/';
        $this->apiKey = config('sportsrc.api_key', '');
        $this->timeout = (int) config('sportsrc.timeout', 30);
        $this->retries = (int) config('sportsrc.retries', 3);
        $this->retryDelay = (int) config('sportsrc.retry_delay', 1000);
    }

    public function getAccount(): array
    {
        return $this->fetch(['type' => 'account'], config('sportsrc.cache_ttl.account', 300));
    }

    public function getSports(): array
    {
        return $this->fetch(['type' => 'sports'], config('sportsrc.cache_ttl.sports', 86400));
    }

    public function getMatches(array $params = [], int $ttl = null): array
    {
        $query = array_merge(['type' => 'matches'], $params);
        return $this->fetch($query, $ttl ?? config('sportsrc.cache_ttl.matches', 300));
    }

    public function getMatchDetail(string $matchId): array
    {
        return $this->fetch(['type' => 'detail', 'id' => $matchId], config('sportsrc.cache_ttl.detail', 120));
    }

    public function getScores(string $matchId): array
    {
        return $this->fetch(['type' => 'scores', 'match_id' => $matchId], config('sportsrc.cache_ttl.scores', 300));
    }

    public function getLineups(string $matchId): array
    {
        return $this->fetch(['type' => 'lineups', 'match_id' => $matchId], config('sportsrc.cache_ttl.lineups', 3600));
    }

    public function getStats(string $matchId): array
    {
        return $this->fetch(['type' => 'stats', 'match_id' => $matchId], config('sportsrc.cache_ttl.stats', 1800));
    }

    public function getIncidents(string $matchId): array
    {
        return $this->fetch(['type' => 'incidents', 'match_id' => $matchId], config('sportsrc.cache_ttl.incidents', 1200));
    }

    public function getH2H(string $matchId): array
    {
        return $this->fetch(['type' => 'h2h', 'match_id' => $matchId], config('sportsrc.cache_ttl.h2h', 3600));
    }

    public function getStandings(string $tournamentId): array
    {
        return $this->fetch(['type' => 'standings', 'tournament_id' => $tournamentId], config('sportsrc.cache_ttl.standings', 1800));
    }

    public function getHighlights(string $matchId): array
    {
        return $this->fetch(['type' => 'highlights', 'match_id' => $matchId], config('sportsrc.cache_ttl.highlights', 300));
    }

    public function getLastMatches(string $teamId, int $limit = 5): array
    {
        return $this->fetch(['type' => 'last_matches', 'team_id' => $teamId, 'limit' => $limit], config('sportsrc.cache_ttl.last_matches', 600));
    }

    public function getGraph(string $matchId): array
    {
        return $this->fetch(['type' => 'graph', 'match_id' => $matchId], config('sportsrc.cache_ttl.graph', 300));
    }

    public function resolveSportFilter(string|int $sport): string
    {
        if (is_numeric($sport)) {
            $sports = $this->getSports();
            $items = $sports['data'] ?? $sports;
            foreach ((array) $items as $item) {
                if (isset($item['id']) && (string) $item['id'] === (string) $sport) {
                    return (string) ($item['slug'] ?? $item['key'] ?? $item['name'] ?? $item['id']);
                }
            }
        }

        return (string) $sport;
    }

    public function getDailyUsage(): int
    {
        return (int) Cache::get($this->getUsageKey(), 0);
    }

    public function getLastErrors(): array
    {
        return Cache::get('sportsrc:last_errors', []);
    }

    private function fetch(array $query, int $ttl): array
    {
        $query = array_filter($query, fn ($value) => $value !== null && $value !== '');
        $cacheKey = $this->buildCacheKey($query);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey, []);
        }

        $response = $this->makeRequest($query);
        if (!empty($response)) {
            Cache::put($cacheKey, $response, now()->addSeconds($ttl));
        }

        return $response;
    }

    private function makeRequest(array $query): array
    {
        if (empty($this->apiKey)) {
            $message = 'SPORTSRC_API_KEY is not configured.';
            $this->logError($message, null, $query);
            return [];
        }

        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->retries) {
            $attempt++;
            try {
                $url = $this->baseUrl;
                $context = ['url' => $url, 'query' => $query, 'attempt' => $attempt];
                Log::channel('sportsrc')->info('SportSRC request', $context);

                $response = Http::withHeaders([
                    'X-API-KEY' => $this->apiKey,
                    'Accept' => 'application/json',
                    'User-Agent' => 'SportStream/1.0',
                ])
                ->timeout($this->timeout)
                ->get($url, $query);

                $status = $response->status();
                $body = $response->body();

                Log::channel('sportsrc')->info('SportSRC response', array_merge($context, [
                    'status' => $status,
                    'body' => $body,
                ]));

                if ($status === 429) {
                    $retryAfter = (int) ($response->header('Retry-After') ?? 60);
                    $this->logError('SportSRC rate limit reached.', $status, $query);
                    if ($attempt < $this->retries) {
                        sleep(min($retryAfter, 60));
                        continue;
                    }
                    return [];
                }

                if ($response->failed()) {
                    $this->logError('SportSRC API responded with failure.', $status, $query, $body);
                    return [];
                }

                $data = $response->json();
                if (!is_array($data)) {
                    $this->logError('SportSRC API returned invalid JSON.', $status, $query, $body);
                    return [];
                }

                $this->incrementUsage();
                return $data;
            } catch (\Exception $exception) {
                $lastException = $exception;
                $this->logError($exception->getMessage(), null, $query, null, $exception);
                if ($attempt < $this->retries) {
                    usleep($this->retryDelay * 1000);
                }
            }
        }

        if ($lastException) {
            $this->logError('SportSRC request exhausted retries.', null, $query, null, $lastException);
        }

        return [];
    }

    private function getUsageKey(): string
    {
        return 'sportsrc:usage:' . date('Y-m-d');
    }

    private function incrementUsage(): void
    {
        $key = $this->getUsageKey();
        $value = (int) Cache::get($key, 0) + 1;
        Cache::put($key, $value, now()->addDay());
    }

    private function buildCacheKey(array $query): string
    {
        ksort($query);
        return 'sportsrc:' . md5(json_encode($query, JSON_THROW_ON_ERROR));
    }

    private function logError(string $message, ?int $status, array $query, ?string $body = null, ?\Throwable $exception = null): void
    {
        $context = [
            'query' => $query,
            'status' => $status,
            'body' => $body,
        ];

        if ($exception) {
            $context['exception'] = $exception->getMessage();
        }

        Log::channel('sportsrc')->error($message, $context);

        $errors = Cache::get('sportsrc:last_errors', []);
        array_unshift($errors, [
            'timestamp' => now()->toDateTimeString(),
            'message' => $message,
            'status' => $status,
            'query' => $query,
            'body' => $body,
            'exception' => $exception?->getMessage(),
        ]);
        Cache::put('sportsrc:last_errors', array_slice($errors, 0, 20), now()->addHours(6));
    }
}
