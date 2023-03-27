<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;

    protected $primaryKey = 'uuid';
    protected $uuidKey = 'uuid';

    protected $fillable = [
        'payment_id',
        'user_id',
        'order_status_id',
        'products',
        'address',
        'delivery_fee',
        'amount',
    ];

    protected $casts = [
        'products' => 'json',
        'address' => 'json',
        'amount' => 'float',
        'deliery_fee' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'uuid');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function products()
    {
        return Product::whereIn('uuid', collect($this->products)->pluck('product'))->get();
    }
}
