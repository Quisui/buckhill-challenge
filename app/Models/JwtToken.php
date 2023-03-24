<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JwtToken extends Model
{
    use HasFactory;
    use Uuid;
    protected $primaryKey = 'unique_id';
    protected $uuidKey = 'unique_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'token_title',
        'restrictions',
        'permissions',
        'expires_at',
        'last_used_at',
        'refreshed_at'
    ];
}
