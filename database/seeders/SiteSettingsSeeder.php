<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            [
                'key' => 'site_name',
                'value' => 'SportStream',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Name',
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Watch Live Sports Streams Free',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Tagline',
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@sportstream.com',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Support Email',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'general',
                'label' => 'Maintenance Mode',
            ],

            // Design & Styling
            [
                'key' => 'primary_color',
                'value' => '#ff3b30',
                'type' => 'text',
                'group' => 'styling',
                'label' => 'Primary Brand Color',
            ],
            [
                'key' => 'default_og_image',
                'value' => 'https://sportstream.com/images/default-og.jpg',
                'type' => 'text',
                'group' => 'styling',
                'label' => 'Default OG Image URL',
            ],

            // Integration & Analytics
            [
                'key' => 'google_analytics_id',
                'value' => '',
                'type' => 'text',
                'group' => 'analytics',
                'label' => 'Google Analytics Tracking ID',
            ],
            [
                'key' => 'custom_header_code',
                'value' => '',
                'type' => 'textarea',
                'group' => 'analytics',
                'label' => 'Custom Header Code (e.g. tracking scripts)',
            ],

            // Monetization
            [
                'key' => 'enable_ads',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'monetization',
                'label' => 'Enable Advertisements Globals',
            ],
        ];

        foreach ($settings as $s) {
            SiteSetting::updateOrCreate(
                ['key' => $s['key']],
                [
                    'value' => $s['value'],
                    'type' => $s['type'],
                    'group' => $s['group'],
                    'label' => $s['label'],
                ]
            );
        }
    }
}
