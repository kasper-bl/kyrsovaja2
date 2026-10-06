<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Временный маршрут для проверки вёрстки
Route::get('/test-register', function () {
    return view('auth.register');
});

// Боевые маршруты — заглушки (сейчас просто возвращают форму)
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    return 'Тут будет обработка регистрации';
})->name('register.post');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    return 'Тут будет обработка входа';
})->name('login.post');

Route::get('/forgot-password', function () {
    return 'Тут будет страница восстановления пароля';
})->name('password.request');