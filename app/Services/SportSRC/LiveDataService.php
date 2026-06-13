<?php

namespace App\Services\SportSRC;

class LiveDataService
{
    public function __construct(
        private SportSRCClient $client
    ) {}

    public function getScores(string $matchId): array
    {
        return $this->client->get('scores', ['match_id' => $matchId], cacheTtl: 15);
    }

    public function getLineups(string $matchId): array
    {
        return $this->client->get('lineups', ['match_id' => $matchId], cacheTtl: 60);
    }

    public function getStats(string $matchId): array
    {
        return $this->client->get('stats', ['match_id' => $matchId], cacheTtl: 30);
    }

    public function getIncidents(string $matchId): array
    {
        return $this->client->get('incidents', ['match_id' => $matchId], cacheTtl: 20);
    }

    public function getH2H(string $matchId): array
    {
        return $this->client->get('h2h', ['match_id' => $matchId], cacheTtl: 3600);
    }

    public function getStandings(string $tournamentId): array
    {
        return $this->client->get('standing', ['tournament_id' => $tournamentId], cacheTtl: 1800);
    }

    public function getGraph(string $matchId): array
    {
        return $this->client->get('graph', ['match_id' => $matchId], cacheTtl: 30);
    }

    public function getHighlights(string $matchId): array
    {
        return $this->client->get('highlights', ['match_id' => $matchId], cacheTtl: 300);
    }

    public function getLastMatches(string $teamId, int $limit = 5): array
    {
        return $this->client->get('last_matches', ['team_id' => $teamId, 'limit' => $limit], cacheTtl: 600);
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