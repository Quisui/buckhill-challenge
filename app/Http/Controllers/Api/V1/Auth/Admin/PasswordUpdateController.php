<?php

namespace App\Http\Controllers\Api\V1\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordUpdateController extends Controller
{
    /**
     * @OA\Put(
     *     path="/admin/forgot-password",
     *     summary="Update your password",
     *     security={{"api_key": {}}},
     *     tags={"Admin/Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password","password_confirmation"},
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
     *             @OA\Property(property="current_password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(response="200", description="User created successfully"),
     *     @OA\Response(response="201", description="User created successfully"),
     *     @OA\Response(response="404", description="Not created"),
     *     @OA\Response(response="401", description="Unauthorized")
     * )
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'password_confirmation' => ['required', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Your password has been updated.',
        ], Response::HTTP_ACCEPTED);
    }
}
