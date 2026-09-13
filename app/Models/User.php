<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasMeta;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes,  HasMeta;

    protected $with = ['meta'];

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'address',
        'id_proof',
        'profile_image',
        'city',
        'pincode'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

}
