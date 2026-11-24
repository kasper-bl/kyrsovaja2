<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function register(string $name, string $email, string $password, string $role = 'user'): User
    {
        return User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
            'role'     => $role,
        ]);
    }

    public function attemptLogin(string $email, string $password): bool
    {
        return Auth::attempt([
            'email'    => $email,
            'password' => $password,
        ]);
    }

    public function logout(): void
    {
        Auth::logout();

        // invalidate — очищает данные сессии
        // regenerate — пересоздаёт ID сессии (защита от session fixation)
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}