<?php

namespace App\Services\Api\V1\JWTAuth;

use App\Helpers\TokenHelper;
use App\Models\JwtToken;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard as AuthGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     *
     * @return void
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
    }

    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $token = $this->getTokenFromRequest();
        if (!$token) {
            $this->handleMissingToken();
        }

        $token = $this->validateToken($token);

        if (is_null($token)) {
            return $token;
        }

        $this->enforceShortLivedTokens($token);

        $this->token = $token;
        $this->user = $this->provider->retrieveByCredentials(['uuid' => $token->user_id]);

        return $this->user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     *
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

    /*
    * Verify That the stored token is still valid so we can still use this same token,
    *
    * If not Log out the user
    */
    public function validateStoredJWTExpiredTime($token)
    {
        $storedToken = JwtToken::with('user')->currentUser($token->user_id)->first();
        if (!app()->environment('testing')) {

            if (empty($storedToken)) {
                return null;
            }

            if ($storedToken->isExpired()) {
                abort(Response::HTTP_UNAUTHORIZED, 'Token expired, please renew your jwt');
            }
        }

        return true;
    }

    private function getTokenFromRequest()
    {
        $request = request();
        return $request->token ?? $request->bearerToken();
    }

    private function handleMissingToken()
    {
        if (env('APP_DEBUG')) {
            throw new \UnexpectedValueException('Missing token. Please provide Bearer Authorization via header, or via "token" parameter.');
        }

        return null; // Laravel will return a 401 Unauthenticated.
    }

    private function validateToken($token)
    {
        try {
            $decodedToken = TokenHelper::jwtDecode($token);
            $expired = $this->validateStoredJWTExpiredTime($decodedToken);
            if (is_null($expired)) return null;
            return $decodedToken;
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
            }
            \Log::error($e);
            return null; // Laravel will return a 401 Unauthenticated.
        }
    }

    private function enforceShortLivedTokens($token)
    {
        $maxMinutes = config('app.jwt_max_exp_minutes');
        if (empty($token->exp)) {
            throw new \UnexpectedValueException('Token exceeds maximum lifetime.');
        }

        if ($token->exp > strtotime("+{$maxMinutes} minutes")) {
            $validMinutes = \Carbon\Carbon::createFromTimestamp($token->exp)->diffInMinutes();
            throw new \UnexpectedValueException('Token exceeds maximum lifetime. Token is valid for ' . $validMinutes . ' minutes, but max lifetime is ' . $maxMinutes . ' minutes.');
        }
    }
}
