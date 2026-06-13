<?php

namespace Database\Seeders;

use App\Models\SeoSetting;
use Illuminate\Database\Seeder;

class SeoSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            'home' => [
                'meta_title_template' => 'Live Sports Streaming - Watch Football, Basketball, Tennis Free | @{{ site_name }}',
                'meta_description_template' => 'Watch free live sports streams in high definition. Football, basketball, tennis and more. Realtime matches, no signups required.',
                'twitter_card' => 'summary_large_image',
            ],
            'match' => [
                'meta_title_template' => 'Watch @{{ title }} Live Stream | @{{ site_name }}',
                'meta_description_template' => 'Watch @{{ team_home }} vs @{{ team_away }} live streaming online. Enjoy HD sports stream coverage, live events, lineups, statistics and scores on @{{ site_name }}.',
                'twitter_card' => 'summary_large_image',
            ],
            'search' => [
                'meta_title_template' => 'Search Results for "@{{ query }}" | @{{ site_name }}',
                'meta_description_template' => 'Find live streaming schedules, upcoming games, and highlights for "@{{ query }}" on @{{ site_name }}.',
                'twitter_card' => 'summary_large_image',
            ],
            'sport' => [
                'meta_title_template' => 'Watch Live @{{ sport_name }} Streams | @{{ site_name }}',
                'meta_description_template' => 'Get the complete schedule of live and upcoming @{{ sport_name }} matches. Watch HD streams, check standings and results on @{{ site_name }}.',
                'twitter_card' => 'summary_large_image',
            ],
            'standings' => [
                'meta_title_template' => '@{{ competition }} Standings & Table | @{{ site_name }}',
                'meta_description_template' => 'View the latest standings, table, and statistics for @{{ competition }} on @{{ site_name }}.',
                'twitter_card' => 'summary_large_image',
            ],
        ];

        foreach ($templates as $pageKey => $data) {
            SeoSetting::updateOrCreate(
                ['page_key' => $pageKey],
                [
                    'meta_title_template' => $data['meta_title_template'],
                    'meta_description_template' => $data['meta_description_template'],
                    'twitter_card' => $data['twitter_card'],
                ]
            );
        }
    }
}
