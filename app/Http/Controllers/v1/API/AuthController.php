<?php

namespace App\Http\Controllers\v1\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Enums\HttpStatusCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token');

        return $this->success(
            data: ['user' => new UserResource($user), 'token' => $token->plainTextToken],
            message: !$user->hasVerifiedEmail() ? 'Please verify your email.' : 'Request allowed',
            code: HttpStatusCode::CREATED->value
        );
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error(
                message: 'Invalid credentials.',
                code: HttpStatusCode::UNAUTHENTICATED->value
            );
        }

        $token = $user->createToken('auth_token');

        return $this->success(
            data: ['user' => new UserResource($user), 'token' => $token->plainTextToken],
            message: !$user->hasVerifiedEmail() ? 'Please verify your email.' : 'Request allowed',
            code: HttpStatusCode::SUCCESSFUL->value
        );
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();

        return $this->success(
            data: [],
            message: 'Logged out successfully.',
            code: HttpStatusCode::SUCCESSFUL->value
        );
    }
}
