<?php

namespace App\Models;

use App\Enums\ProductCategory;
use App\Support\Money;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category',
        'name',
        'slug',
        'description',
        'options',
        'base_price',
        'ready_min_days',
        'ready_max_days',
        'image',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'category' => ProductCategory::class,
            'base_price' => 'decimal:2',
            'is_active' => 'boolean',
            'ready_min_days' => 'integer',
            'ready_max_days' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Products customers can see, in shop order. */
    public function scopeForSale(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $term).'%';

        return $query->where(fn (Builder $q) => $q
            ->where('name', 'like', $like)
            ->orWhere('description', 'like', $like)
            ->orWhere('category', 'like', $like));
    }

    public function price(): string
    {
        return Money::peso($this->base_price);
    }

    /** "4–7 days" or "1 day" */
    public function readyIn(): string
    {
        if ($this->ready_min_days === $this->ready_max_days) {
            return $this->ready_min_days.' '.str('day')->plural($this->ready_min_days);
        }

        return "{$this->ready_min_days}–{$this->ready_max_days} days";
    }

    public function imageUrl(): string
    {
        return asset($this->image ?: 'images/five-stitches-logo.png');
    }
}
