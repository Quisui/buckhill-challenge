<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\AuthRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/admin/create",
     *     summary="Get all users",
     *     tags={"Admin/Auth"},
     *     security={{"api_key": {}}},
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function index()
    {
        return User::avoidMe()->get();
    }

    /**
     * @OA\Post(
     *     path="/admin/create",
     *     summary="Admin Create a new User",
     *     security={{"api_key": {}}},
     *     tags={"Admin/Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password","password_confirmation","first_name","last_name","is_admin","is_marketing"},
     *             @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
     *             @OA\Property(property="first_name", type="string", example="First Name"),
     *             @OA\Property(property="last_name", type="string", example="last Name"),
     *             @OA\Property(property="is_admin", type="boolean", example="true"),
     *             @OA\Property(property="is_marketing", type="boolean", example="false")
     *         )
     *     ),
     *     @OA\Response(response="200", description="User created successfully"),
     *     @OA\Response(response="201", description="User created successfully"),
     *     @OA\Response(response="404", description="Not created"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */
    public function store(AuthRequest $request)
    {
        $user = User::create([
            'first_name' => $request->validated('first_name'),
            'last_name' => $request->validated('last_name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
            'is_admin' => $request->validated('is_admin'),
            'is_marketing' => $request->validated('is_marketing'),
        ]);

        event(new Registered($user));

        $token = $user->createJWT();
        $storedJWT = $this->storeJWT($token['token']);

        return response()->json([
            'token_unique_id' => $storedJWT->unique_id,
            'access_token' => $token['token'],
            'token_expiry_text' => $token['token_expiry_text'],
            'token_expiry_seconds' => $token['token_expiry_seconds'],
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
