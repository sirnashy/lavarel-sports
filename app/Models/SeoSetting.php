<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    protected $fillable = [
        'page_key', 'meta_title_template', 'meta_description_template',
        'og_image', 'twitter_card', 'extra_meta',
    ];

    protected $casts = [
        'extra_meta' => 'array',
    ];
}