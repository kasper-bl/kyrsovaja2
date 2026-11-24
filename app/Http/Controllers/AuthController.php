<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = $this->authService->register(
            $request->name,
            $request->email,
            $request->password
        );

        auth()->login($user);

        return redirect()->route('home');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if ($this->authService->attemptLogin($credentials['email'], $credentials['password'])) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()
            ->withErrors(['email' => 'Неверная почта или пароль.'])
            ->onlyInput('email');
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('home');
    }
}