<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $order_id
 * @property int $customer_id
 * @property string|null $customer_name
 * @property int $package_id
 * @property int $amount
 * @property string|null $description
 * @property string $status
 * @property string|null $payment_type
 * @property string|null $transaction_id
 * @property string|null $midtrans_transaction_id
 * @property string|null $fraud_status
 * @property string|null $signature_key
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'order_id',
        'customer_id',
        'customer_name',
        'package_id',
        'amount',
        'description',
        'status',
        'payment_type',
        'transaction_id',
        'midtrans_transaction_id',
        'fraud_status',
        'signature_key',
    ];

    /**
     * Relasi ke Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Relasi ke Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /**
     * Relasi ke Order
     * transactions.order_id → orders.id
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
