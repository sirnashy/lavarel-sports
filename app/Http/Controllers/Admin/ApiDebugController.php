<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SportSrcService;

class ApiDebugController extends Controller
{
    public function index(SportSrcService $sportSrc)
    {
        $account = $sportSrc->getAccount();
        $sports = $sportSrc->getSports();
        $matches = $sportSrc->getMatches(['status' => 'inprogress', 'limit' => 20]);
        $errors = $sportSrc->getLastErrors();
        $usage = $sportSrc->getDailyUsage();

        return view('admin.api-debug.index', compact('account', 'sports', 'matches', 'errors', 'usage'));
    }
}
