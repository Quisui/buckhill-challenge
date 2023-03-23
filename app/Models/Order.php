<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, Uuid;

    protected $primaryKey = 'uuid';
    protected $uuidKey = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'payment_id',
        'user_id',
        'order_status_id',
        'payment_id',
        'products',
        'address',
        'delivery_fee',
        'amount'
    ];
}
