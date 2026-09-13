<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineBookingPayment extends Model
{
    protected $table = 'offline_booking_payments';

    protected $fillable = [
        'booking_id',
        'paid_amount',
        'payment_mode',
        'transaction_id',
        'payment_gateway_order_id',
        'transferred_owner',
        'payment_screenshot',
        'payment_date',
        'receivedby',
        'transferred_owner'
    ];

    // 🔗 Belongs To Booking
    public function booking()
    {
        return $this->belongsTo(OfflineBooking::class, 'booking_id');
    }
}