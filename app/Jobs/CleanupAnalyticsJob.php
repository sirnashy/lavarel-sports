<?php

namespace App\Jobs;

use App\Models\Visitor;
use App\Models\StreamView;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanupAnalyticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Visitor::where('created_at', '<', now()->subDays(90))->delete();
        StreamView::where('created_at', '<', now()->subDays(90))->delete();
    }
}