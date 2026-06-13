<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Advertisement extends Model
{
    protected $fillable = [
        'name', 'slot_key', 'code', 'position',
        'sort_order', 'is_active', 'starts_at', 'ends_at', 'impressions',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function scopeActive(Builder $query): Builder
    {
        $now = Carbon::now();
        return $query->where('is_active', true)
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
            ->orderBy('sort_order');
    }

    public function scopeForSlot(Builder $query, string $slot): Builder
    {
        return $query->where('slot_key', $slot);
    }
}