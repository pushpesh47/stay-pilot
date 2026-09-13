<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class OfflineBooking extends Model
{
     use SoftDeletes;
    protected $table = 'offline_bookings';

    protected $fillable = [
        'user_id',
        'property_id',
        'show_booking_id',
        'total_guests',
        'phone',
        'check_in',
        'check_out',
        'source',
        'payment_mode',
        'cash_received_by',
        'per_day_price',
        'transferred_to_owner',
        'owner_payment_screenshot',
        'status',
        'extra_guest_charge',
        'early_checkin_charges',
        'late_checkout_charges',
        'damage_charges',
        'total_charges',
        'booking_days',
        'total_amount',
        'paid_amount',
        'offline-booking',
        'by_refernece'
    ];

    // 🔗 Guests Relation
    public function guests()
    {
        return $this->hasMany(OfflineBookingGuest::class, 'booking_id');
    }

    // 🔗 Payments Relation
    public function payments()
    {
        return $this->hasMany(OfflineBookingPayment::class, 'booking_id');
    }

    // 🔗 Extensions Relation
    public function extensions()
    {
        return $this->hasMany(OfflineBookingExtension::class, 'booking_id');
    }


    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function booker()
    {
        return $this->belongsTo(User::class);
    }
}
