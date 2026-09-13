<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BranchImage extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id','image_path'];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}


