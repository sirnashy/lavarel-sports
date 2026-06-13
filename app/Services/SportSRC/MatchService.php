<?php

namespace App\Services\SportSRC;

use Illuminate\Support\Facades\Cache;

class MatchService
{
    public function __construct(
        private SportSRCClient $client
    ) {}

    public function getLiveMatches(int $sportId = null): array
    {
        $params = ['status' => 'live'];
        if ($sportId) $params['sport_id'] = $sportId;
        return $this->client->get('matches', $params, cacheTtl: 30);
    }

    public function getUpcomingMatches(int $sportId = null, int $limit = 20): array
    {
        $params = ['status' => 'upcoming', 'limit' => $limit];
        if ($sportId) $params['sport_id'] = $sportId;
        return $this->client->get('matches', $params, cacheTtl: 120);
    }

    public function getFinishedMatches(int $sportId = null, int $limit = 20): array
    {
        $params = ['status' => 'finished', 'limit' => $limit];
        if ($sportId) $params['sport_id'] = $sportId;
        return $this->client->get('matches', $params, cacheTtl: 300);
    }

    public function getMatchDetail(string $matchId): array
    {
        return $this->client->get('detail', ['match_id' => $matchId], cacheTtl: 30);
    }

    public function searchMatches(string $query, int $limit = 20): array
    {
        return $this->client->get('matches', ['search' => $query, 'limit' => $limit], cacheTtl: 60);
    }

    public function getMatchesBySport(int $sportId, string $status = null): array
    {
        $params = ['sport_id' => $sportId];
        if ($status) $params['status'] = $status;
        return $this->client->get('matches', $params, cacheTtl: 60);
    }

    public function getMatchesByCompetition(string $competitionId): array
    {
        return $this->client->get('matches', ['competition_id' => $competitionId], cacheTtl: 120);
    }
}