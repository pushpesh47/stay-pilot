<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class PropertyType extends Model
{
    use SoftDeletes;

    protected $table = "property_types";
    protected $fillable = ['name' ,'status'];

    
}