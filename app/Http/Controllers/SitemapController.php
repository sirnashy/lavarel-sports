<?php

namespace App\Http\Controllers;

use App\Models\SitePage;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'hourly'],
            ['loc' => url('/search'), 'priority' => '0.7', 'changefreq' => 'daily'],
        ];

        $pages = SitePage::active()->get();
        foreach ($pages as $page) {
            $urls[] = [
                'loc' => url('/page/' . $page->slug),
                'priority' => '0.5',
                'changefreq' => 'weekly',
                'lastmod' => $page->updated_at->toAtomString(),
            ];
        }

        return response()
            ->view('sitemap.index', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}