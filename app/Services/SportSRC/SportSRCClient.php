<?php

namespace App\Services\SportSRC;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

class SportSRCClient
{
    private string $baseUrl;
    private string $apiKey;
    private int $timeout;
    private int $retries;
    private int $retryDelay;

    public function __construct()
    {
        $this->baseUrl = config('sportsrc.base_url', 'https://api.sportsrc.org/v2/');
        $this->apiKey = config('sportsrc.api_key', '');
        $this->timeout = config('sportsrc.timeout', 30);
        $this->retries = config('sportsrc.retries', 3);
        $this->retryDelay = config('sportsrc.retry_delay', 1000);
    }

    public function get(string $endpoint, array $params = [], int $cacheTtl = 60): array
    {
        $cacheKey = $this->buildCacheKey($endpoint, $params);

        if ($cacheTtl > 0) {
            $cached = Cache::get($cacheKey);
            if ($cached !== null) {
                Log::debug('SportSRC cache hit', ['endpoint' => $endpoint, 'key' => $cacheKey]);
                return $cached;
            }
        }

        $response = $this->makeRequest($endpoint, $params);

        if ($cacheTtl > 0 && !empty($response)) {
            Cache::put($cacheKey, $response, now()->addSeconds($cacheTtl));
        }

        return $response;
    }

    private function makeRequest(string $endpoint, array $params = []): array
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < $this->retries) {
            $attempt++;
            try {
                Log::info('SportSRC API request', [
                    'endpoint' => $endpoint,
                    'params' => $params,
                    'attempt' => $attempt,
                ]);

                $response = Http::withHeaders([
                    'X-API-KEY' => $this->apiKey,
                    'Accept' => 'application/json',
                    'User-Agent' => 'SportStream/1.0',
                ])
                ->timeout($this->timeout)
                ->get($this->baseUrl . ltrim($endpoint, '/'), $params);

                if ($response->status() === 429) {
                    $retryAfter = (int) ($response->header('Retry-After') ?? 60);
                    Log::warning('SportSRC rate limited', ['retry_after' => $retryAfter]);
                    sleep(min($retryAfter, 60));
                    continue;
                }

                if ($response->failed()) {
                    Log::error('SportSRC API error', [
                        'endpoint' => $endpoint,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    return [];
                }

                $data = $response->json();
                $this->incrementApiUsage($endpoint);

                return is_array($data) ? $data : [];

            } catch (\Exception $e) {
                $lastException = $e;
                Log::error('SportSRC request exception', [
                    'endpoint' => $endpoint,
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                ]);

                if ($attempt < $this->retries) {
                    usleep($this->retryDelay * 1000 * $attempt);
                }
            }
        }

        Log::critical('SportSRC all retries exhausted', ['endpoint' => $endpoint]);
        return [];
    }

    private function buildCacheKey(string $endpoint, array $params): string
    {
        return 'sportsrc:' . md5($endpoint . serialize($params));
    }

    private function incrementApiUsage(string $endpoint): void
    {
        $key = 'sportsrc:usage:' . date('Y-m-d');
        Cache::increment($key);
        Cache::expire($key, now()->addDays(7));

        $endpointKey = 'sportsrc:usage:endpoint:' . $endpoint . ':' . date('Y-m-d');
        Cache::increment($endpointKey);
        Cache::expire($endpointKey, now()->addDays(7));
    }

    public function flushCache(string $endpoint = null): void
    {
        if ($endpoint) {
            Cache::forget('sportsrc:' . md5($endpoint));
        }
    }

    public function getDailyUsage(): int
    {
        return (int) Cache::get('sportsrc:usage:' . date('Y-m-d'), 0);
    }
}