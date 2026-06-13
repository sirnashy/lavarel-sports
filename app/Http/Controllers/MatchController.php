<?php

namespace App\Http\Controllers;

use App\Models\StreamView;
use App\Services\MetaBuilder;
use App\Services\SportSRC\LiveDataService;
use App\Services\SportSRC\MatchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MatchController extends Controller
{
    public function __construct(
        private MatchService $matchService,
        private LiveDataService $liveDataService,
        private MetaBuilder $metaBuilder,
    ) {}

    public function detail(string $matchId)
    {
        $match = $this->matchService->getMatchDetail($matchId);

        if (empty($match)) {
            abort(404, 'Match not found');
        }

        $matchData = $match['data'] ?? $match;
        $homeTeam = $matchData['home_team'] ?? [];
        $awayTeam = $matchData['away_team'] ?? [];
        $competition = $matchData['competition'] ?? [];

        $meta = $this->metaBuilder
            ->title(($homeTeam['name'] ?? 'Home') . ' vs ' . ($awayTeam['name'] ?? 'Away') . ' - Live Stream')
            ->description('Watch ' . ($homeTeam['name'] ?? '') . ' vs ' . ($awayTeam['name'] ?? '') . ' live stream online. Match stats, lineups, and live score updates.')
            ->canonical(url('/match/' . $matchId))
            ->build();

        return view('match.detail', compact('match', 'matchData', 'homeTeam', 'awayTeam', 'competition', 'meta', 'matchId'));
    }

    public function stream(string $matchId)
    {
        $match = $this->matchService->getMatchDetail($matchId);

        if (empty($match)) {
            abort(404, 'Match not found');
        }

        $matchData = $match['data'] ?? $match;
        $streams = $matchData['streams'] ?? [];

        StreamView::create([
            'match_id' => $matchId,
            'session_id' => session()->getId(),
            'ip_address' => request()->ip(),
            'stream_source' => $streams[0]['source'] ?? 'unknown',
        ]);

        $meta = $this->metaBuilder
            ->title('Watch ' . ($matchData['home_team']['name'] ?? '') . ' vs ' . ($matchData['away_team']['name'] ?? '') . ' Live')
            ->canonical(url('/match/' . $matchId . '/stream'))
            ->build();

        return view('match.stream', compact('match', 'matchData', 'streams', 'meta', 'matchId'));
    }

    public function liveData(string $matchId): JsonResponse
    {
        $data = $this->liveDataService->getAllMatchData($matchId);
        return response()->json($data);
    }

    public function standings(string $matchId): JsonResponse
    {
        $match = $this->matchService->getMatchDetail($matchId);
        $matchData = $match['data'] ?? $match;
        $tournamentId = $matchData['tournament_id'] ?? $matchData['competition']['id'] ?? null;

        if (!$tournamentId) {
            return response()->json([]);
        }

        return response()->json($this->liveDataService->getStandings($tournamentId));
    }

    public function h2h(string $matchId): JsonResponse
    {
        return response()->json($this->liveDataService->getH2H($matchId));
    }

    public function highlights(string $matchId): JsonResponse
    {
        return response()->json($this->liveDataService->getHighlights($matchId));
    }
}