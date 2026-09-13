<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory, SoftDeletes ;

    protected $fillable = [
        'branch_id','property_name','property_number','property_type_id','default_guests','max_adults','max_children','max_capacity','base_price','extra_guest_charge','property_notes','short_description','description','amenities','status'
    ];

    protected $casts = [
        'amenities' => 'array'
    ];

    // protected $appends = ['amenity_details'];

    public function images() {
        return $this->hasMany(PropertyImage::class);
    }

    public function propertyType() {
        return $this->belongsTo(PropertyType::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getAmenityDetailsAttribute()
    {
        return Amenity::whereIn('id', $this->amenities ?? [])
            ->select('id', 'name', 'icon', 'amenity_type')
            ->get();
    }

    public function isPropertyBooked($checkInDate, $checkOutDate, $ignoreBookingId = null): bool
    {
        $checkIn = Carbon::parse($checkInDate)->format('Y-m-d H:i:s');
        $checkOut = Carbon::parse($checkOutDate)->format('Y-m-d H:i:s');

        $query = OfflineBooking::query()
            ->where('property_id', $this->id)
            ->where('status', 'active');

        if ($ignoreBookingId) {
            $query->where('id', '!=', $ignoreBookingId);
        }

        $query->where(function ($q) use ($checkIn, $checkOut) {
            $q->where('check_in', '<', $checkOut)
                ->where('check_out', '>', $checkIn);
        });

        return $query->exists();
    }
}

