<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Регистрация нового пользователя.
     */
    public function register(string $name, string $email, string $password, string $role = 'user'): User
    {
        return User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
            'role'     => $role,
        ]);
    }

    /**
     * Попытка аутентификации пользователя.
     *
     * @return bool true — если вход успешен
     */
    public function attemptLogin(string $email, string $password): bool
    {
        return Auth::attempt([
            'email'    => $email,
            'password' => $password,
        ]);
    }

    /**
     * Выход пользователя + очистка сессии.
     */
    public function logout(): void
    {
        Auth::logout();

        // invalidate — очищает данные сессии
        // regenerate — пересоздаёт ID сессии (защита от session fixation)
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}