<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\TokenHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @OA\Post(
     * path="/admin/login",
     * summary="Sign in",
     * description="Login by email, password, both user and admin uses this endpoint so te behavior will change just on the route name, remember, you need to send your bearer token",
     * operationId="authLogin",
     * tags={"Admin/Auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="admin@buckhill.co.uk"),
     *       @OA\Property(property="password", type="string", format="password", example="admin")
     *    ),
     * ),
     * @OA\Response(response="200", description="Success"),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
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
     *
     * @return \Illuminate\Http\Response
     */
    public function sendLoginResponse(LoginRequest $request)
    {
        $this->clearLoginAttempts($request);
        $user = $this->guard()->user();
        $device = substr($request->userAgent() ?? '', 0, 255);
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
}
