<?php

namespace App\Jobs;

use App\Models\SitePage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateSitemapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function handle(): void
    {
        try {
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

            $xml = view('sitemap.index', compact('urls'))->render();
            Storage::disk('public')->put('sitemap.xml', $xml);
            Log::info('Sitemap generated');
        } catch (\Exception $e) {
            Log::error('Sitemap generation failed', ['error' => $e->getMessage()]);
        }
    }
}