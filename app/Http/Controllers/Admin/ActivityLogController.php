<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->get('action'), fn($q, $action) => $q->where('action', $action))
            ->when($request->get('user_id'), fn($q, $userId) => $q->where('user_id', $userId))
            ->latest()
            ->paginate(50);

        return view('admin.logs.index', compact('logs'));
    }
}