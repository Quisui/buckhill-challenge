<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, Uuid;

    protected $primaryKey = 'uuid';
    protected $uuidKey = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'category_uuid',
        'title',
        'price',
        'description',
        'metadata'
    ];
}
