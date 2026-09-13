<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GatewayPayment extends Model
{
    use SoftDeletes;
    protected $table = 'gateway_payments';

    protected $fillable = [
        'user_id',
        'room_id',
        'gateway',
        'gateway_order_id',
        'gateway_payment_id',
        'gateway_signature',
        'amount',
        'status',
        'request_payload',
        'response_payload',
        'paid_at'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
