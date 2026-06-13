<?php

namespace App\Http\Controllers;

use App\Models\FeaturedMatch;
use App\Services\MetaBuilder;
use App\Services\SportSRC\MatchService;
use App\Services\SportSRC\SportService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        private MatchService $matchService,
        private SportService $sportService,
        private MetaBuilder $metaBuilder,
    ) {}

    public function index(Request $request)
    {
        $sportId = $request->get('sport');

        $liveMatches = $this->matchService->getLiveMatches($sportId);
        $upcomingMatches = $this->matchService->getUpcomingMatches($sportId, 12);
        $finishedMatches = $this->matchService->getFinishedMatches($sportId, 12);
        $sports = $this->sportService->getAllSports();

        $featuredMatches = FeaturedMatch::active()
            ->with('creator')
            ->limit(6)
            ->get();

        $meta = $this->metaBuilder
            ->forPage('home')
            ->canonical(url('/'))
            ->build();

        return view('home.index', compact(
            'liveMatches', 'upcomingMatches', 'finishedMatches',
            'sports', 'featuredMatches', 'meta', 'sportId'
        ));
    }
}