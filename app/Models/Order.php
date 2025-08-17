<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'payment_status',
        'is_complete',
        'is_delivery',
        'customer_name',
        'contact_phone',
        'delivery_address',
        'items',
        'total_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'items' => 'array', // Automatically encode/decode the items JSON
    ];
}
