<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'amount',
        'property_id',
        'received_by',
        'expense_by',
        'payment_mode',
        'notes',
        'expense_date',
        'created_by'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
