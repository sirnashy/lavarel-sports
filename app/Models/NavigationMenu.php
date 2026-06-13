<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NavigationMenu extends Model
{
    protected $fillable = [
        'label', 'url', 'target', 'icon', 'location',
        'parent_id', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(NavigationMenu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavigationMenu::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeForLocation(Builder $query, string $location): Builder
    {
        return $query->where('location', $location);
    }

    public function scopeRootLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }
}