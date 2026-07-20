<?php

namespace App\Models\Cart;

use App\Models\Catalog\ProductVariant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'cart_id',
        'product_variant_id',
        'quantity',
        'price',
        'customizations',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'customizations' => 'json',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function getTotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    public function getFormattedTotalAttribute(): string
    {
        return currency($this->total);
    }

    public function scopeForCart($query, $cartId)
    {
        return $query->where('cart_id', $cartId);
    }
}
