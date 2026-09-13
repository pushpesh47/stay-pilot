<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    use HasFactory;

    protected $table = 'user_metas';

    protected $fillable = [
        'user_id',
        'meta_key',
        'meta_value',
    ];

    // Automatically cast meta_value to array
    protected $casts = [
        'meta_value' => 'array',
    ];

    // Relationship back to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
