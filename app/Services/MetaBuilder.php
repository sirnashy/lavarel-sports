<?php

namespace App\Services;

use App\Models\SeoSetting;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class MetaBuilder
{
    private array $meta = [];
    private ?string $siteName = null;
    private string $siteUrl;

    public function __construct()
    {
        $this->siteUrl = config('app.url');
    }

    private function loadSiteName(): string
    {
        if ($this->siteName !== null) {
            return $this->siteName;
        }

        $this->siteName = config('app.name');

        if (Schema::hasTable((new SiteSetting())->getTable())) {
            $this->siteName = SiteSetting::get('site_name', $this->siteName);
        }

        return $this->siteName;
    }

    public function forPage(string $pageKey, array $variables = []): self
    {
        if (! Schema::hasTable((new SeoSetting())->getTable())) {
            return $this;
        }

        try {
            $setting = Cache::remember('seo:' . $pageKey, 3600, fn() =>
                SeoSetting::where('page_key', $pageKey)->first()
            );

            if ($setting) {
                $this->meta['title'] = $this->interpolate($setting->meta_title_template, $variables);
                $this->meta['description'] = $this->interpolate($setting->meta_description_template, $variables);
                $this->meta['og_image'] = $setting->og_image ?? $this->getDefaultOgImage();
                $this->meta['twitter_card'] = $setting->twitter_card ?? 'summary_large_image';
            }
        } catch (\Throwable) {
            // Database may not be ready yet (installation or test environment)
        }

        return $this;
    }

    private function getDefaultOgImage(): string
    {
        if (Schema::hasTable((new SiteSetting())->getTable())) {
            return SiteSetting::get('default_og_image', asset('images/og-default.jpg'));
        }

        return asset('images/og-default.jpg');
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