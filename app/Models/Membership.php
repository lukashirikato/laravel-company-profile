<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Membership extends Model
{
    use HasFactory;

    protected $table = 'memberships';

    protected $fillable = [
        'name',
        'price',
        'duration',
        'description',
        'status'
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
