<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    use Uuid;
    public $incrementing = false;

    protected $primaryKey = 'uuid';
    protected $uuidKey = 'uuid';

    protected $fillable = [
        'title',
        'slug',
    ];
}
