<?php

namespace App\Http\Controllers;

use App\Services\MetaBuilder;
use App\Services\SportSRC\MatchService;
use App\Services\SportSRC\SportService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        private MatchService $matchService,
        private SportService $sportService,
        private MetaBuilder $metaBuilder,
    ) {}

    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $results = [];

        if (strlen($query) >= 2) {
            $results = $this->matchService->searchMatches($query);
        }

        $sports = $this->sportService->getAllSports();

        $meta = $this->metaBuilder
            ->title('Search Matches' . ($query ? " - $query" : ''))
            ->description('Search for live and upcoming sports matches, teams and competitions.')
            ->canonical(url('/search'))
            ->build();

        return view('search.index', compact('query', 'results', 'sports', 'meta'));
    }
}