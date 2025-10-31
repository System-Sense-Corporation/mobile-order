<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

<<<<<<< HEAD
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_date',
        'delivery_date',
        'customer',
        'product',
        'quantity',
        'notes',
=======
    protected $fillable = [
        'customer_name',
        'items',
        'status',
        'received_at',
    ];

    protected $casts = [
        'received_at' => 'datetime',
>>>>>>> origin/codex/replace-closure-route-with-controller-action
    ];
}
