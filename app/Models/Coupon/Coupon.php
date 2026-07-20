<?php

namespace App\Models\Coupon;

use App\Models\Order\Order;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory;
    use HasUuids;
    use LogsActivity;
    use SoftDeletes;

    public const TYPE_PERCENTAGE = 'percentage';

    public const TYPE_FIXED = 'fixed';

    public const TYPES = [
        self::TYPE_PERCENTAGE,
        self::TYPE_FIXED,
    ];

    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_order',
        'maximum_discount',
        'usage_limit',
        'usage_per_customer',
        'starts_at',
        'expires_at',
        'is_active',
        'apply_to_shipping',
        'product_ids',
        'category_ids',
        'brand_ids',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_order' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_per_customer' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'apply_to_shipping' => 'boolean',
        'product_ids' => 'json',
        'category_ids' => 'json',
        'brand_ids' => 'json',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id');
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, CouponUsage::class);
    }

    public function getUsageCountAttribute(): int
    {
        return $this->usages()->count();
    }

    public function getRemainingUsesAttribute(): ?int
    {
        if ($this->usage_limit === null) {
            return null;
        }

        return max(0, $this->usage_limit - $this->usage_count);
    }

    public function isValidForOrder($orderAmount): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isAfter(now())) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isBefore(now())) {
            return false;
        }

        if ($this->minimum_order > 0 && $orderAmount < $this->minimum_order) {
            return false;
        }

        if ($this->usage_limit !== null && $this->remaining_uses <= 0) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($orderAmount): float
    {
        if ($this->type === self::TYPE_PERCENTAGE) {
            $discount = ($orderAmount * $this->value) / 100;
            if ($this->maximum_discount !== null) {
                $discount = min($discount, $this->maximum_discount);
            }

            return $discount;
        }

        return min($this->value, $orderAmount);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereColumn('usage_limit', '>',
                    fn ($q) => $q->selectRaw('(SELECT COUNT(*) FROM coupon_usages WHERE coupon_id = coupons.id)'));
            });
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }
}
