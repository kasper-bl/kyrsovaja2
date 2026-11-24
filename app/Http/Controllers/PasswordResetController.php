<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewPasswordRequest;
use App\Http\Requests\PasswordResetRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Показать форму запроса сброса пароля.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Обработать отправку ссылки для сброса пароля.
     */
    public function sendResetLink(PasswordResetRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => 'Ссылка для сброса пароля отправлена на вашу почту.'])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Показать форму ввода нового пароля.
     */
    public function showResetPassword(string $token)
    {
        return view('auth.new-password', ['token' => $token]);
    }

    /**
     * Обработать сохранение нового пароля.
     */
    public function updatePassword(NewPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('status', 'Пароль успешно изменён! Войдите с новым паролем.')
            : back()->withErrors(['email' => __($status)]);
    }
}