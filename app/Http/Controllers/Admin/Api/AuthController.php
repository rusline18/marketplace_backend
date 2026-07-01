<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Api;

use App\Domain\Users\Models\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Api\LoginAdminRequest;
use App\Http\Resources\Admin\AdminResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Authenticate a staff member and issue an API token.
     *
     * @param  LoginAdminRequest  $request  The validated login credentials.
     *
     * @throws ValidationException If the credentials are invalid.
     */
    public function login(LoginAdminRequest $request): JsonResponse
    {
        $admin = Admin::where('email', $request->string('email')->toString())->first();

        if (! $admin || ! Hash::check($request->string('password')->toString(), $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        return response()->json([
            'admin' => new AdminResource($admin),
            'token' => $admin->createToken('admin-api')->plainTextToken,
        ]);
    }

    /**
     * Revoke the token used for the current request.
     *
     * @param  Request  $request  The incoming request.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user('admin')->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    /**
     * Show the authenticated staff member's profile.
     *
     * @param  Request  $request  The incoming request.
     */
    public function me(Request $request): AdminResource
    {
        return new AdminResource($request->user('admin'));
    }
}
