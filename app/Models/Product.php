<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, Uuid;
    use SoftDeletes;

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $uuidKey = 'uuid';

    protected $fillable = [
        'category_uuid',
        'title',
        'price',
        'description',
        'metadata',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_uuid');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }

    /**
     * Note that this approach can be less efficient than a traditional many-to-many relationship, since it requires querying the Product model for each order.
     * we can instead use a order_products to make it more efficient
     */
    public function getProductsAttribute($value)
    {
        $products = collect(json_decode($value));

        return Product::whereIn('uuid', $products->pluck('product'))->get()
            ->keyBy('uuid')
            ->map(function ($product) use ($products) {
                $product->quantity = $products->where('product', $product->uuid)->first()->quantity;
                return $product;
            });
    }
}
