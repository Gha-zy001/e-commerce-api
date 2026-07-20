<?php

namespace App\Models\Order;

use App\Models\Auth\Customer;
use App\Models\Auth\CustomerAddress;
use App\Models\Coupon\Coupon;
use App\Models\Payment\Payment;
use App\Models\Shipping\ShippingMethod;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use HasUuids;
    use LogsActivity;
    use SoftDeletes;

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_SHIPPED = 'shipped';

    public const STATUS_DELIVERED = 'delivered';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_REFUNDED = 'refunded';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_PROCESSING,
        self::STATUS_SHIPPED,
        self::STATUS_DELIVERED,
        self::STATUS_CANCELLED,
        self::STATUS_REFUNDED,
    ];

    public const PAYMENT_STATUS_PENDING = 'pending';

    public const PAYMENT_STATUS_PAID = 'paid';

    public const PAYMENT_STATUS_PARTIALLY_PAID = 'partially_paid';

    public const PAYMENT_STATUS_REFUNDED = 'refunded';

    public const PAYMENT_STATUS_FAILED = 'failed';

    public const SHIPPING_STATUS_PENDING = 'pending';

    public const SHIPPING_STATUS_PROCESSING = 'processing';

    public const SHIPPING_STATUS_SHIPPED = 'shipped';

    public const SHIPPING_STATUS_DELIVERED = 'delivered';

    public const SHIPPING_STATUS_RETURNED = 'returned';

    protected $fillable = [
        'customer_id',
        'customer_address_id',
        'shipping_method_id',
        'coupon_id',
        'order_number',
        'status',
        'subtotal',
        'shipping_amount',
        'discount_amount',
        'tax_amount',
        'total',
        'currency',
        'payment_status',
        'shipping_status',
        'notes',
        'shipping_data',
        'billing_data',
        'admin_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'shipping_data' => 'json',
        'billing_data' => 'json',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function address()
    {
        return $this->belongsTo(CustomerAddress::class, 'customer_address_id');
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return currency($this->subtotal, $this->currency);
    }

    public function getFormattedShippingAmountAttribute(): string
    {
        return currency($this->shipping_amount, $this->currency);
    }

    public function getFormattedDiscountAmountAttribute(): string
    {
        return currency($this->discount_amount, $this->currency);
    }

    public function getFormattedTaxAmountAttribute(): string
    {
        return currency($this->tax_amount, $this->currency);
    }

    public function getFormattedTotalAttribute(): string
    {
        return currency($this->total, $this->currency);
    }

    public function getItemCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function getUniqueItemCountAttribute(): int
    {
        return $this->items->count();
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    public function scopeByShippingStatus($query, $status)
    {
        return $query->where('shipping_status', $status);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('order_number', 'like', '%'.$search.'%')
            ->orWhereHas('customer', fn ($q) => $q->where('first_name', 'like', '%'.$search.'%')
                ->orWhere('last_name', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%'));
    }
}
