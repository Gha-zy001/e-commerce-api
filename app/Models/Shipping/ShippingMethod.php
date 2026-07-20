<?php

namespace App\Models\Shipping;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'carrier',
        'is_active',
        'sort_order',
        'base_cost',
        'free_shipping',
        'min_order_amount',
        'estimated_delivery_min',
        'estimated_delivery_max',
        'configuration',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'base_cost' => 'decimal:2',
        'free_shipping' => 'boolean',
        'min_order_amount' => 'decimal:2',
        'estimated_delivery_min' => 'integer',
        'estimated_delivery_max' => 'integer',
        'configuration' => 'json',
    ];

    public function translations()
    {
        return $this->hasMany(ShippingMethodTranslation::class, 'shipping_method_id');
    }

    public function getNameAttribute(): ?string
    {
        return $this->translations->where('locale', app()->getLocale())->first()?->name
            ?? $this->translations->first()?->name
            ?? $this->code;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->translations->where('locale', app()->getLocale())->first()?->description;
    }

    public function getEstimatedDeliveryAttribute(): ?string
    {
        if ($this->estimated_delivery_min && $this->estimated_delivery_max) {
            if ($this->estimated_delivery_min === $this->estimated_delivery_max) {
                return $this->estimated_delivery_min.' '.trans('shipping.days');
            }

            return $this->estimated_delivery_min.'-'.$this->estimated_delivery_max.' '.trans('shipping.days');
        }

        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('sort_order')->orderBy('code');
    }

    public function scopeFreeShipping($query)
    {
        return $query->where('free_shipping', true);
    }
}
