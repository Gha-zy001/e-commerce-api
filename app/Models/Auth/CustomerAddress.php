<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'customer_id',
        'label',
        'country',
        'governorate',
        'city',
        'district',
        'street',
        'building',
        'floor',
        'apartment',
        'postal_code',
        'latitude',
        'longitude',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFullAddressAttribute(): string
    {
        $parts = [];

        if ($this->street) {
            $parts[] = $this->street;
        }
        if ($this->building) {
            $parts[] = 'Bldg. '.$this->building;
        }
        if ($this->floor) {
            $parts[] = 'Floor '.$this->floor;
        }
        if ($this->apartment) {
            $parts[] = 'Apt. '.$this->apartment;
        }
        if ($this->district) {
            $parts[] = $this->district;
        }
        if ($this->city) {
            $parts[] = $this->city;
        }
        if ($this->governorate) {
            $parts[] = $this->governorate;
        }
        if ($this->country) {
            $parts[] = $this->country;
        }
        if ($this->postal_code) {
            $parts[] = $this->postal_code;
        }

        return implode(', ', $parts);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
