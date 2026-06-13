<?php

namespace App\Console\Commands;

use App\Services\SportSrcService;
use Illuminate\Console\Command;

class SportSrcTest extends Command
{
    protected $signature = 'sportsrc:test';
    protected $description = 'Validate SportSRC API connectivity and load key resources.';

    public function handle(SportSrcService $sportSrc): int
    {
        $this->info('Running SportSRC API integration test...');

        $account = $sportSrc->getAccount();
        if (empty($account)) {
            $this->error('✗ API Key Invalid or SportSRC account endpoint failed.');
            $this->line(json_encode($sportSrc->getLastErrors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            return self::FAILURE;
        }

        $this->info('✓ API Key Valid');

        $sports = $sportSrc->getSports();
        if (empty($sports)) {
            $this->error('✗ Failed to load sports categories.');
            $this->line(json_encode($sportSrc->getLastErrors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            return self::FAILURE;
        }

        $this->info('✓ Sports Loaded');

        $matches = $sportSrc->getMatches(['status' => 'inprogress', 'limit' => 10]);
        if (empty($matches)) {
            $this->warn('⚠ Matches endpoint returned no data.');
        } else {
            $this->info('✓ Matches Loaded');
        }

        $this->line('API Usage Today: ' . $sportSrc->getDailyUsage());
        $this->line('Last Errors:');
        $this->line(json_encode($sportSrc->getLastErrors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return self::SUCCESS;
    }
}
