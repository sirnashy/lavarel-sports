<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FeaturedMatch extends Model
{
    protected $fillable = [
        'match_id', 'title', 'description', 'thumbnail',
        'sort_order', 'is_active', 'match_starts_at', 'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'match_starts_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}