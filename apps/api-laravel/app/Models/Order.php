<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'items',
        'amount',
        'fee',
        'total_payment',
        'payment_method',
        'payment_number',
        'status',
        'notes',
        'redirect_url',
        'expired_at',
        'paid_at',
    ];

    protected $casts = [
        'items' => 'array',
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function isPaid(): bool
    {
        return $this->status === 'paid' || $this->status === 'processing' || $this->status === 'shipped' || $this->status === 'completed';
    }

    public function isExpired(): bool
    {
        return $this->expired_at && now()->greaterThan($this->expired_at) && $this->status === 'waiting_payment';
    }
}
