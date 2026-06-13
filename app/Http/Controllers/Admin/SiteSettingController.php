<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SeoSetting;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('label')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            SiteSetting::set($key, $value);
        }

        Cache::flush(); // Clear all cached settings
        ActivityLog::record('updated', 'Updated site settings');
        return back()->with('success', 'Settings saved.');
    }

    public function seo()
    {
        $seoSettings = SeoSetting::all()->keyBy('page_key');
        $pageKeys = ['home', 'match', 'search', 'sport', 'standings'];
        return view('admin.settings.seo', compact('seoSettings', 'pageKeys'));
    }

    public function updateSeo(Request $request)
    {
        $settings = $request->get('seo', []);

        foreach ($settings as $pageKey => $data) {
            SeoSetting::updateOrCreate(
                ['page_key' => $pageKey],
                [
                    'meta_title_template' => $data['meta_title_template'] ?? '',
                    'meta_description_template' => $data['meta_description_template'] ?? '',
                    'twitter_card' => $data['twitter_card'] ?? 'summary_large_image',
                ]
            );
        }

        Cache::forget('seo:*');
        ActivityLog::record('updated', 'Updated SEO settings');
        return back()->with('success', 'SEO settings saved.');
    }
}