<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\JwtToken;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * @OA\Post(
     * path="/admin/logout",
     * summary="Sign in",
     * description="Logout your current user, both user and admin uses this endpoint so te behavior will change just on the route name, remember, you need to send your bearer token",
     * operationId="authLogout",
     * tags={"Admin/Auth"},
     * security={{"api_key": {}}},
     *
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
     * @OA\Response(response="204", description="Success logout"),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * ),
     *
     * @OA\Response(response="500", description="Missing token"),
     */
    public function __invoke(Request $request)
    {
        JwtToken::where('user_id', $request->user()->uuid)->delete();

        return response()->noContent();
    }
}
