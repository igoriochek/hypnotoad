<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'badge',
        'title',
        'description',
        'meta',
        'price_display',
        'price_cents',
        'requires_shipping',
        'payable',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'badge' => 'array',
        'title' => 'array',
        'description' => 'array',
        'meta' => 'array',
        'price_display' => 'array',
        'price_cents' => 'integer',
        'requires_shipping' => 'boolean',
        'payable' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function localizedTitle(string $locale): string
    {
        return $this->title[$locale] ?? $this->title['en'];
    }

    public function localizedDescription(string $locale): string
    {
        return $this->description[$locale] ?? $this->description['en'];
    }

    public function localizedBadge(string $locale): string
    {
        return $this->badge[$locale] ?? $this->badge['en'];
    }

    public function localizedMeta(string $locale): string
    {
        return $this->meta[$locale] ?? $this->meta['en'];
    }

    public function localizedPrice(string $locale): string
    {
        return $this->price_display[$locale] ?? $this->price_display['en'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
