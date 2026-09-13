<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfflineBookingGuest extends Model
{
    protected $table = 'offline_booking_guests';

    protected $fillable = [
        'booking_id',
        'name',
        'phone',
        'aadhaar',
    ];

    // 🔗 Belongs To Booking
    public function booking()
    {
        return $this->belongsTo(OfflineBooking::class, 'booking_id');
    }
}