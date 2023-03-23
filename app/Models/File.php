<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory, Uuid;

    protected $primaryKey = 'uuid';
    protected $uuidKey = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'path',
        'size',
        'type'
    ];
}
