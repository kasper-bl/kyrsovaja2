@extends('layouts.main')

@section('title', 'Сброс пароля')

@section('content')
    <section class="registration wrapper">
        <div class="registration__title">
            <h2>УКАЖИТЕ ПОЧТУ</h2>
            <p>Мы отправим ссылку для сброса пароля</p>
        </div>

        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="registration__form">
                <label>Почта</label>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
                <input type="email" name="email" placeholder="Введите почту" value="{{ old('email') }}">
            </div>

            <button type="submit" class="button">Отправить ссылку</button>
        </form>

        <div class="registration__footer">
            <a href="{{ route('login') }}">вернуться ко входу</a>
        </div>
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection