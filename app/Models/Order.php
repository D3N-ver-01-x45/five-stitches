<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\ReleaseMethod;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_contact',
        'release_method',
        'delivery_address',
        'date_needed',
        'payment_method',
        'down_payment_paid',
        'status',
        'notes',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_method' => PaymentMethod::class,
            'release_method' => ReleaseMethod::class,
            'down_payment_paid' => 'boolean',
            'date_needed' => 'date',
            'total' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        // Order numbers look like FS-001 (from the business plan).
        static::created(function (Order $order) {
            $order->order_number = 'FS-'.str_pad((string) $order->id, 3, '0', STR_PAD_LEFT);
            $order->saveQuietly();
        });
    }

    /** URLs use the order number, e.g. /admin/orders/FS-012. */
    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeStatus(Builder $query, ?OrderStatus $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    /** 50% down payment for made-to-order items. */
    public function downPayment(): string
    {
        return number_format((float) $this->total / 2, 2, '.', '');
    }

    /** Colors of all items, e.g. "Sage, Coral". */
    public function colorSummary(): string
    {
        return $this->items->pluck('color')->filter()->unique()->implode(', ') ?: '—';
    }

    public function totalQuantity(): int
    {
        return (int) $this->items->sum('quantity');
    }
}
