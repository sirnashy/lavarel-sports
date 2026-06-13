<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\StreamView;
use App\Models\Visitor;
use App\Services\SportSrcService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(SportSrcService $client)
    {
        $todayVisitors = Visitor::whereDate('created_at', today())->count();
        $weekVisitors = Visitor::where('created_at', '>=', now()->subDays(7))->count();
        $todayStreams = StreamView::whereDate('created_at', today())->count();
        $apiUsage = $client->getDailyUsage();

        $popularMatches = StreamView::select('match_id', DB::raw('count(*) as views'))
            ->whereDate('created_at', today())
            ->groupBy('match_id')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $visitorChart = Visitor::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count')
        )->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard.index', compact(
            'todayVisitors', 'weekVisitors', 'todayStreams', 'apiUsage',
            'popularMatches', 'recentLogs', 'visitorChart'
        ));
    }
}