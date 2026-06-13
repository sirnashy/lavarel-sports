<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SitePage extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'meta_title', 'meta_description',
        'og_image', 'is_active', 'show_in_nav', 'sort_order', 'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_nav' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}