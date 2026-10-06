<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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