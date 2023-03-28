<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;

    protected $primaryKey = 'uuid';
    protected $uuidKey = 'uuid';

    protected $fillable = [
        'title',
        'slug',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
