<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\TokenHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Models\JwtToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function __invoke(LoginRequest $request)
    {
        // the login attempts for this application. We'll key this by the email and
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->post('browser_timezone')) {
                session(['browser_timezone' => $request->post('browser_timezone')]);
            }
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login api endpoint. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Send the response after the user was authenticated.
     * Override from AuthenticatesUsers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendLoginResponse(LoginRequest $request)
    {
        $this->clearLoginAttempts($request);
        $user = $this->guard()->user();
        $device  = substr($request->userAgent() ?? '', 0, 255);
        $token = TokenHelper::jwtEncode([
            'user_id' => $user->uuid,
            'device' => $device,
            'iss' => config('app.url'),
            'exp' => Carbon::now()->addMinutes(config('app.jwt_max_exp_minutes'))->getTimestamp(),
        ]);
        $tokenExpiry = Carbon::createFromTimestamp(TokenHelper::jwtDecode($token)->exp);
        $storedJWT = $this->storeJWT($token);
        session(['JWTUniqueKey' => $storedJWT->unique_id]);

        return response()->json([
            'unique_id' => $storedJWT->unique_id,
            'token' => $token,
            'token_expiry_text' => $tokenExpiry->diffForHumans(),
            'token_expiry_seconds' => $tokenExpiry->diffInSeconds(),
        ]);
    }

    protected function storeJWT(string $token)
    {
        $tokenExpiry = Carbon::createFromTimestamp(TokenHelper::jwtDecode($token)->exp);
        $userId = auth()->user()->uuid;

        return JwtToken::create([
            'user_id' => $userId,
            'token_title' => 'auth',
            'restrictions' => '{"restrictions": "*"}',
            'permissions' => '{"permissions": "*"}',
            'expires_at' => $tokenExpiry,
            'last_used_at' => Carbon::now(),
            'refreshed_at' => Carbon::now(),
        ]);
    }
}
