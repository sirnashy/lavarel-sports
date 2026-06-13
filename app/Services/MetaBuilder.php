<?php

namespace App\Services;

use App\Models\SeoSetting;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class MetaBuilder
{
    private array $meta = [];
    private string $siteName;
    private string $siteUrl;

    public function __construct()
    {
        $this->siteName = SiteSetting::get('site_name', config('app.name'));
        $this->siteUrl = config('app.url');
    }

    public function forPage(string $pageKey, array $variables = []): self
    {
        try {
            $setting = Cache::remember('seo:' . $pageKey, 3600, fn() =>
                SeoSetting::where('page_key', $pageKey)->first()
            );

            if ($setting) {
                $this->meta['title'] = $this->interpolate($setting->meta_title_template, $variables);
                $this->meta['description'] = $this->interpolate($setting->meta_description_template, $variables);
                $this->meta['og_image'] = $setting->og_image ?? SiteSetting::get('default_og_image');
                $this->meta['twitter_card'] = $setting->twitter_card ?? 'summary_large_image';
            }
        } catch (\Throwable) {
            // Table may not exist yet (e.g. test environment)
        }

        return $this;
    }

    public function title(string $title): self
    {
        $this->meta['title'] = $title . ' | ' . $this->siteName;
        return $this;
    }

    public function description(string $desc): self
    {
        $this->meta['description'] = $desc;
        return $this;
    }

    public function ogImage(string $url): self
    {
        $this->meta['og_image'] = $url;
        return $this;
    }

    public function canonical(string $url): self
    {
        $this->meta['canonical'] = $url;
        return $this;
    }

    public function build(): array
    {
        return array_merge([
            'title' => $this->siteName,
            'description' => 'Watch live sports streams online',
            'og_image' => asset('images/og-default.jpg'),
            'twitter_card' => 'summary_large_image',
            'canonical' => url()->current(),
            'site_name' => $this->siteName,
        ], $this->meta);
    }

    private function interpolate(string $template, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }
}