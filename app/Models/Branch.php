<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Branch extends Model
{
    use HasFactory, SoftDeletes ;

    protected $fillable = [
        'name','description','short_description','city_id','location','pincode','reception_number','check_in_time','check_out_time','amenities','house_rules','status'
    ];

    protected $casts = [
        'amenities' => 'array',
        // 'house_rules' => 'array',
    ];

    // protected $appends = ['amenity_details'];

    public function images() {
        return $this->hasMany(BranchImage::class);
    }

    public function properties() {
        return $this->hasMany(Property::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function getAmenityDetailsAttribute()
    {
        return Amenity::whereIn('id', $this->amenities ?? [])
            ->select('id', 'name','icon', 'amenity_type')
            ->get();
    }
}

