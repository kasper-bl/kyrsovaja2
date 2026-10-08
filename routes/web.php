<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\NewPasswordRequest;
use App\Http\Controllers\ProfileController;


// Главная (пока заглушка — потом сделаем настоящую)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Регистрация
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Вход
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Выход (только для авторизованных)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Восстановление пароля (пока заглушка)
Route::get('/forgot-password', function () {
    return 'Тут будет страница восстановления пароля';
})->name('password.request');

// Форма «Забыли пароль» (GET)
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

// Отправка ссылки на почту (POST)
Route::post('/forgot-password', function (PasswordResetRequest $request) {
    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::ResetLinkSent
        ? back()->with(['status' => 'Ссылка для сброса пароля отправлена на вашу почту.'])
        : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

// Форма ввода нового пароля (GET) — сюда пользователь попадает по ссылке из письма
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.new-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// Сохранение нового пароля (POST)
Route::post('/reset-password', function (NewPasswordRequest $request) {
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
})->middleware('guest')->name('password.update');


// Просмотр профиля (пока заглушка — сделаем после избранного и подборок)
Route::get('/profile', function () {
    return 'Страница профиля (в разработке)';
})->middleware('auth')->name('profile');

// Редактирование профиля
Route::get('/profile/edit', [ProfileController::class, 'showEdit'])->middleware('auth')->name('profile.edit');
Route::post('/profile/edit', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');