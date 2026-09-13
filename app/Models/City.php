<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class City extends Model
{
    use SoftDeletes;
    protected $fillable = ['name' ,'state','status','slug'];

    
     protected static function boot()
    {
        parent::boot();

        static::creating(function ($city) {
            $city->slug = Str::slug($city->name);
        });

        static::updating(function ($city) {
            $city->slug = Str::slug($city->name);
        });
    }

    
}