<?php

namespace App\Services;

use App\Models\Advertisement;
use Illuminate\Support\Facades\Cache;

use Illuminate\Database\Eloquent\Collection;

class AdManager
{
    public function getAdsForSlot(string $slot): Collection
    {
        $cacheKey = 'ads:slot:' . $slot;
        $ads = Cache::get($cacheKey);

        if ($ads instanceof Collection) {
            return $ads;
        }

        if (! is_array($ads)) {
            $ads = Advertisement::active()->forSlot($slot)->get()->toArray();
            Cache::put($cacheKey, $ads, 300);
        }

        return Advertisement::hydrate($ads);
    }

    public function renderSlot(string $slot): string
    {
        $ads = $this->getAdsForSlot($slot);

        if ($ads->isEmpty()) {
            return '';
        }

        $html = '';
        foreach ($ads as $ad) {
            Advertisement::where('id', $ad->id)->increment('impressions');
            $html .= '<div class="ad-unit" data-slot="' . e($slot) . '">' . $ad->code . '</div>';
        }
        return $html;
    }

    public function clearCache(string $slot = null): void
    {
        if ($slot) {
            Cache::forget('ads:slot:' . $slot);
        } else {
            foreach (['header','sidebar','in-article','video','mobile','footer'] as $s) {
                Cache::forget('ads:slot:' . $s);
            }
        }
    }
}