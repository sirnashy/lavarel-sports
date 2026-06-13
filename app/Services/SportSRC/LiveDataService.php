<?php

namespace App\Services\SportSRC;

use App\Services\SportSrcService;

class LiveDataService
{
    public function __construct(
        private SportSrcService $client
    ) {}

    public function getScores(string $matchId): array
    {
        return $this->client->getScores($matchId);
    }

    public function getLineups(string $matchId): array
    {
        return $this->client->getLineups($matchId);
    }

    public function getStats(string $matchId): array
    {
        return $this->client->getStats($matchId);
    }

    public function getIncidents(string $matchId): array
    {
        return $this->client->getIncidents($matchId);
    }

    public function getH2H(string $matchId): array
    {
        return $this->client->getH2H($matchId);
    }

    public function getStandings(string $tournamentId): array
    {
        return $this->client->getStandings($tournamentId);
    }

    public function getGraph(string $matchId): array
    {
        return $this->client->getGraph($matchId);
    }

    public function getHighlights(string $matchId): array
    {
        return $this->client->getHighlights($matchId);
    }

    public function getLastMatches(string $teamId, int $limit = 5): array
    {
        return $this->client->getLastMatches($teamId, $limit);
    }

    public function getAllMatchData(string $matchId): array
    {
        return [
            'scores' => $this->getScores($matchId),
            'lineups' => $this->getLineups($matchId),
            'stats' => $this->getStats($matchId),
            'incidents' => $this->getIncidents($matchId),
        ];
    }
}