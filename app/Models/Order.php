<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'delivery_fee' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', auth()->user()->uuid);
        });
    }

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
        return $this->belongsTo(Payment::class, 'payment_id', 'uuid');
    }

    public function orderProducts()
    {
        $productIds = collect(json_decode($this->products))->pluck('product')->toArray();
        return $this->hasMany(Product::class, 'uuid', $productIds);
    }

    public function products(string $productsJson)
    {
        $productIds = collect(json_decode($productsJson))->pluck('product')->toArray();
        return Product::whereIn('uuid', $productIds)->get();
    }
}
