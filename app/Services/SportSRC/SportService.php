<?php

namespace App\Services\SportSRC;

use App\Services\SportSrcService;

class SportService
{
    public function __construct(
        private SportSrcService $client
    ) {}

    public function getAllSports(): array
    {
        return $this->client->getSports();
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