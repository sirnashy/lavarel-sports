<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && !$request->is('admin*') && !$request->ajax()) {
            try {
                Visitor::create([
                    'session_id' => session()->getId(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'page_url' => $request->fullUrl(),
                    'referer' => $request->header('referer'),
                ]);
            } catch (\Exception $e) {
                // Non-blocking
            }
        }

        return $response;
    }
}