<?php

namespace App\Jobs;

use App\Services\SportSRC\MatchService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RefreshLiveMatchesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function handle(MatchService $matchService): void
    {
        try {
            // Force bypass cache for fresh data
            $matchService->getLiveMatches();
            Log::info('Live matches refreshed');
        } catch (\Exception $e) {
            Log::error('Failed to refresh live matches', ['error' => $e->getMessage()]);
        }
    }
}