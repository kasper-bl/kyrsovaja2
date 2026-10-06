@extends('layouts.main')

@section('title', 'Регистрация')

@section('content')
    <section class="registration wrapper">
        <div class="registration__title">
            <h2>РЕГИСТРАЦИЯ</h2>
            <p>Заполните поля своими данными</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="registration__form">
                <label>Имя</label>
                <input type="text" name="name" placeholder="Введите имя" value="{{ old('name') }}">
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

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

            <button type="submit" class="button">Регистрация</button>
        </form>

        <div class="registration__footer">
            <a href="{{ route('login') }}">уже есть аккаунт?</a>
        </div>
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection