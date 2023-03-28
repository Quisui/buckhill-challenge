<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory, Uuid;
    public $incrementing = false;

    protected $primaryKey = 'uuid';
    protected $uuidKey = 'uuid';

    protected $fillable = [
        'title',
        'content',
        'metadata',
    ];
}
