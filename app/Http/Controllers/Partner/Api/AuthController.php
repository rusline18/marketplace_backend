<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Domain\Partners\Actions\RegisterPartnerAction;
use App\Domain\Partners\Enums\PartnerStatus;
use App\Domain\Partners\Models\Partner;
use App\Http\Controllers\Controller;
use App\Http\Requests\Partner\Api\LoginPartnerRequest;
use App\Http\Requests\Partner\Api\RegisterPartnerRequest;
use App\Http\Resources\Partner\PartnerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new partner account, pending admin approval.
     *
     * @param  RegisterPartnerRequest  $request  The validated registration attributes.
     * @param  RegisterPartnerAction  $action  The action that creates the partner.
     */
    public function register(RegisterPartnerRequest $request, RegisterPartnerAction $action): JsonResponse
    {
        $partner = $action->handle($request->validated());

        return response()->json([
            'partner' => new PartnerResource($partner),
        ], 201);
    }

    /**
     * Authenticate a partner and issue an API token.
     *
     * @param  LoginPartnerRequest  $request  The validated login credentials.
     *
     * @throws ValidationException If the credentials are invalid or the account is not yet approved.
     */
    public function login(LoginPartnerRequest $request): JsonResponse
    {
        $partner = Partner::where('email', $request->string('email')->toString())->first();

        if (! $partner || ! Hash::check($request->string('password')->toString(), $partner->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if ($partner->status !== PartnerStatus::Approved) {
            throw ValidationException::withMessages([
                'email' => ['Your partner account is pending approval.'],
            ]);
        }

        return response()->json([
            'partner' => new PartnerResource($partner),
            'token' => $partner->createToken('partner-api')->plainTextToken,
        ]);
    }

    /**
     * Revoke the token used for the current request.
     *
     * @param  Request  $request  The incoming request.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user('partner')->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    /**
     * Show the authenticated partner's profile.
     *
     * @param  Request  $request  The incoming request.
     */
    public function me(Request $request): PartnerResource
    {
        return new PartnerResource($request->user('partner'));
    }
}
