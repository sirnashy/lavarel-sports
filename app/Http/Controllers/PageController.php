<?php

namespace App\Http\Controllers;

use App\Models\SitePage;
use App\Services\MetaBuilder;

class PageController extends Controller
{
    public function __construct(private MetaBuilder $metaBuilder) {}

    public function show(string $slug)
    {
        $page = SitePage::active()->where('slug', $slug)->firstOrFail();

        $meta = $this->metaBuilder
            ->title($page->meta_title ?: $page->title)
            ->description($page->meta_description ?: '')
            ->canonical(url('/page/' . $page->slug))
            ->build();

        if ($page->og_image) {
            $meta['og_image'] = $page->og_image;
        }

        return view('pages.show', compact('page', 'meta'));
    }
}