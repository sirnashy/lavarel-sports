<?php

namespace App\Services\SportSRC;

use App\Services\SportSrcService;

class MatchService
{
    public function __construct(
        private SportSrcService $client
    ) {}

    public function getLiveMatches(string|int $sport = null): array
    {
        $params = ['status' => 'inprogress'];
        if ($sport) {
            $params['sport'] = $this->client->resolveSportFilter($sport);
        }

        return $this->client->getMatches($params, config('sportsrc.cache_ttl.matches', 300));
    }

    public function getUpcomingMatches(string|int $sport = null, int $limit = 20): array
    {
        $params = ['status' => 'upcoming', 'limit' => $limit];
        if ($sport) {
            $params['sport'] = $this->client->resolveSportFilter($sport);
        }

        return $this->client->getMatches($params, config('sportsrc.cache_ttl.matches', 300));
    }

    public function getFinishedMatches(string|int $sport = null, int $limit = 20): array
    {
        $params = ['status' => 'finished', 'limit' => $limit];
        if ($sport) {
            $params['sport'] = $this->client->resolveSportFilter($sport);
        }

        return $this->client->getMatches($params, config('sportsrc.cache_ttl.matches', 300));
    }

    public function getMatchDetail(string $matchId): array
    {
        return $this->client->getMatchDetail($matchId);
    }

    public function searchMatches(string $query, int $limit = 20): array
    {
        return $this->client->getMatches(['search' => $query, 'limit' => $limit], config('sportsrc.cache_ttl.matches', 300));
    }

    public function getMatchesBySport(string|int $sport, string $status = null): array
    {
        $params = ['sport' => $this->client->resolveSportFilter($sport)];
        if ($status) {
            $params['status'] = $status;
        }

        return $this->client->getMatches($params, config('sportsrc.cache_ttl.matches', 300));
    }

    public function getMatchesByCompetition(string $competitionId): array
    {
        return $this->client->getMatches(['competition_id' => $competitionId], config('sportsrc.cache_ttl.matches', 300));
    }
}