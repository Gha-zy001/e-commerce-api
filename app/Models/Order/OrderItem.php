<?php

namespace App\Models\Order;

use App\Models\Catalog\ProductVariant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'product_name',
        'sku',
        'product_slug',
        'brand_name',
        'price',
        'compare_price',
        'quantity',
        'discount_amount',
        'tax_amount',
        'total',
        'variant_attributes',
        'customizations',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'quantity' => 'integer',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'variant_attributes' => 'json',
        'customizations' => 'json',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return currency($this->price, $this->order->currency);
    }

    public function getFormattedTotalAttribute(): string
    {
        return currency($this->total, $this->order->currency);
    }

    public function getFormattedDiscountAmountAttribute(): string
    {
        return currency($this->discount_amount, $this->order->currency);
    }

    public function getFormattedTaxAmountAttribute(): string
    {
        return currency($this->tax_amount, $this->order->currency);
    }

    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }
}
