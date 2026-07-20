<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;
    use HasUuids;

    public const TYPE_AUTHORIZATION = 'authorization';

    public const TYPE_CAPTURE = 'capture';

    public const TYPE_REFUND = 'refund';

    public const TYPE_VOID = 'void';

    public const TYPES = [
        self::TYPE_AUTHORIZATION,
        self::TYPE_CAPTURE,
        self::TYPE_REFUND,
        self::TYPE_VOID,
    ];

    public const STATUS_PENDING = 'pending';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public const STATUS_DECLINED = 'declined';

    protected $fillable = [
        'payment_id',
        'type',
        'gateway',
        'gateway_transaction_id',
        'amount',
        'currency',
        'status',
        'request_data',
        'response_data',
        'error_message',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'request_data' => 'json',
        'response_data' => 'json',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return currency($this->amount, $this->currency);
    }

    public function scopeByPayment($query, $paymentId)
    {
        return $query->where('payment_id', $paymentId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
