<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipCustomer extends Model
{
    use HasFactory;

    protected $table = 'membership_customers';

    protected $fillable = [
        'customer_id',
        'membership_id',
        'start_date',
        'end_date',
        'status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
}
