<?php

namespace App\Models\Order;

use App\Models\Auth\Admin;
use App\Models\Auth\Customer;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'order_id',
        'status',
        'previous_status',
        'notes',
        'admin_id',
        'customer_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeLatestFirst($query)
    {
        return $query->latest();
    }
}
