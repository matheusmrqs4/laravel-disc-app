<?php

namespace App\Services;

use App\Exceptions\AuthenticationException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    /**
     * @param array $data
     * @return array
     */
    public function registerUser(array $data): array
    {
        $user = User::create($data);
        $token = $user->createToken('Access Token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * @param array $credentials
     * @return array
     * @throws AuthenticationException
     */
    public function loginUser(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw AuthenticationException::invalidCredentials();
        }

        $user = Auth::user();
        $token = $user->createToken('Access Token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * @return void
     */
    public function logoutUser(): void
    {
        Auth::user()->tokens()->delete();
    }

    /**
     * @return array
     * @throws AuthenticationException
     */
    public function getAuthenticatedUser(): array
    {
        $user = Auth::user();

        if (!$user) {
            throw AuthenticationException::unauthorized();
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ];
    }
}