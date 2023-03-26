<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\TokenHelper;
use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use Uuid;
    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $uuidKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function createJWT()
    {
        $token = TokenHelper::jwtEncode([
            'user_id' => $this->uuid,
            'iss' => config('app.url'),
            'exp' => Carbon::now()->addMinutes(config('app.jwt_max_exp_minutes'))->getTimestamp(),
        ]);
        $tokenExpiry = Carbon::createFromTimestamp(TokenHelper::jwtDecode($token)->exp);

        return [
            'token' => $token,
            'token_expiry_text' => $tokenExpiry->diffForHumans(),
            'token_expiry_seconds' => $tokenExpiry->diffInSeconds(),
        ];
    }

    public function scopeAvoidMe($query)
    {
        return $query->whereNotIn('uuid', [auth()->user()->uuid]);
    }
}
