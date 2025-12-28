<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $fillable = [
        'customer_id',
        'program',
        'check_in',
        'check_out',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
