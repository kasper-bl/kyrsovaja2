@extends('layouts.main')

@section('title', 'Новый пароль')

@section('content')
    <section class="registration wrapper">
        <div class="registration__title">
            <h2>ВВЕДИТЕ НОВЫЙ ПАРОЛЬ</h2>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="registration__form">
                <label>Почта</label>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
                <input type="email" name="email" placeholder="Введите почту" value="{{ old('email') }}">
            </div>

            <div class="registration__form">
                <label>Новый пароль</label>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
                <input type="password" name="password" placeholder="Введите пароль">
            </div>

            <div class="registration__form">
                <label>Подтверждение пароля</label>
                <input type="password" name="password_confirmation" placeholder="Введите пароль ещё раз">
            </div>

            <button type="submit" class="button">Подтвердить</button>
        </form>
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection