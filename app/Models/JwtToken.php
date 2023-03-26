<?php

namespace App\Models;

use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JwtToken extends Model
{
    use HasFactory;
    use Uuid;
    public $incrementing = false;
    protected $primaryKey = 'unique_id';
    protected $uuidKey = 'unique_id';

    protected $fillable = [
        'user_id',
        'token_title',
        'restrictions',
        'permissions',
        'expires_at',
        'last_used_at',
        'refreshed_at',
    ];

    public function scopeCurrentUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function isExpired()
    {
        return Carbon::now()->gt($this->exp_time);
    }
}
