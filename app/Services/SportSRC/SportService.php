<?php

namespace App\Services\SportSRC;

class SportService
{
    public function __construct(
        private SportSRCClient $client
    ) {}

    public function getAllSports(): array
    {
        return $this->client->get('sports', [], cacheTtl: 3600);
    }

    public function getSportById(int $id): array
    {
        $sports = $this->getAllSports();
        $items = $sports['data'] ?? $sports;
        foreach ((array) $items as $sport) {
            if (($sport['id'] ?? null) == $id) {
                return $sport;
            }
        }
        return [];
    }
}