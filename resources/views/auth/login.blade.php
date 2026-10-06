@extends('layouts.main')

@section('title', 'Авторизация')

@section('content')
    <section class="registration wrapper">
        <div class="registration__title">
            <h2>АВТОРИЗАЦИЯ</h2>
            <p>Введите данные от аккаунта</p>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="registration__form">
                <label>Почта</label>
                <input type="email" name="email" placeholder="Введите почту" value="{{ old('email') }}">
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="registration__form">
                <label>Пароль</label>
                <input type="password" name="password" placeholder="Введите пароль">
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="button">Войти</button>
        </form>

        <div class="registration__footer">
            <a href="{{ route('password.request') }}">забыли пароль?</a>
        </div>
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection