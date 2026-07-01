<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Users\Actions\RegisterUserAction;
use App\Domain\Users\Actions\UpdateProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginUserRequest;
use App\Http\Requests\Api\V1\RegisterUserRequest;
use App\Http\Requests\Api\V1\UpdateProfileRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new buyer account and issue an API token.
     *
     * @param  RegisterUserRequest  $request  The validated registration attributes.
     * @param  RegisterUserAction  $action  The action that creates the user.
     */
    public function register(RegisterUserRequest $request, RegisterUserAction $action): JsonResponse
    {
        $user = $action->handle($request->validated());

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('api')->plainTextToken,
        ], 201);
    }

    /**
     * Authenticate a buyer and issue an API token.
     *
     * @param  LoginUserRequest  $request  The validated login credentials.
     *
     * @throws ValidationException If the credentials are invalid.
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $user = User::where('email', $request->string('email')->toString())->first();

        if (! $user || ! Hash::check($request->string('password')->toString(), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        return response()->json([
            'user' => new UserResource($user),
            'token' => $user->createToken('api')->plainTextToken,
        ]);
    }

    /**
     * Revoke the token used for the current request.
     *
     * @param  Request  $request  The incoming request.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    /**
     * Show the authenticated buyer's profile.
     *
     * @param  Request  $request  The incoming request.
     */
    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    /**
     * Update the authenticated buyer's profile.
     *
     * @param  UpdateProfileRequest  $request  The validated profile attributes.
     * @param  UpdateProfileAction  $action  The action that performs the update.
     */
    public function updateProfile(UpdateProfileRequest $request, UpdateProfileAction $action): UserResource
    {
        return new UserResource($action->handle($request->user(), $request->validated()));
    }
}
