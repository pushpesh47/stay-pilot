<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineBookingExtension extends Model
{
    protected $table = 'offline_booking_extensions';

    protected $fillable = [
        'booking_id',
        'old_checkout',
        'new_checkout',
        'extra_days',
        'extra_amount'
    ];

    // 🔗 Belongs To Booking
    public function booking()
    {
        return $this->belongsTo(OfflineBooking::class, 'booking_id');
    }
}