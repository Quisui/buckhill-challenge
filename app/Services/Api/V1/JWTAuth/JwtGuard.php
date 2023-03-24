<?php

namespace App\Services\Api\V1\JWTAuth;

use App\Helpers\TokenHelper;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard as AuthGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Traits\Macroable;

class JwtGuard implements AuthGuard
{
    use GuardHelpers, Macroable;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     *
     * @throws \UnexpectedValueException
     */
    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $request = request();
        $token = $request->token ?? $request->bearerToken();
        if (!$token) {
            if (env('APP_DEBUG')) {
                throw new \UnexpectedValueException('Missing token. Please provide Bearer Authorization via header, or via "token" parameter.');
            } else {
                return null; // Laravel will return a 401 Unauthenticated.
            }
        }

        try {
            $token = TokenHelper::jwtDecode($token);
        } catch (\Firebase\JWT\ExpiredException $e) {
            return null; // Laravel will return a 401 Unauthenticated.
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            if (app()->environment('testing')) {
                return throw $e;
            }
            return null; // Laravel will return a 401 Unauthenticated.
        } catch (\DomainException | \InvalidArgumentException | \UnexpectedValueException $e) {
            if (env('APP_DEBUG')) {
                throw $e;
            } else {
                \Log::error($e);

                return null; // Laravel will return a 401 Unauthenticated.
            }
        }

        // enforce short-lived tokens
        $maxMinutes = config('app.jwt_max_exp_minutes');
        if ($token->exp > strtotime("+$maxMinutes minutes")) {
            $validMinutes = \Carbon\Carbon::createFromTimestamp($token->exp)->diffInMinutes();
            throw new \UnexpectedValueException('Token exceeds maximum lifetime. Token is valid for ' . $validMinutes . ' minutes, but max lifetime is ' . $maxMinutes . ' minutes.');
        }

        $this->token = $token;
        $this->user = $this->provider->retrieveByCredentials(['uuid' => $token->user_id]);

        return $this->user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (empty($credentials['token'])) {
            return false;
        }

        if ($this->provider->retrieveByCredentials(['id' => $credentials['token']])) {
            return true;
        }

        return false;
    }
}
