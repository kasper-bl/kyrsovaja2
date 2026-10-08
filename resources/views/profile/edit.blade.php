@extends('layouts.main')

@section('title', 'Редактирование профиля')

@section('content')
    <section class="user-profile wrapper">
        <div class="user-profile__avatar">
            @if ($user->avatar)
                <img class="user-profile__avatar-image" src="{{ asset($user->avatar) }}" alt="Фото профиля">
            @else
                <img class="user-profile__avatar-image" src="{{ asset('img/user_photo_default.png') }}" alt="Фото профиля">
            @endif
        </div>

        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form class="user-profile__form form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="form__group">
                <label class="form__label">Заменить фото профиля</label>
                <div class="form__file-input-wrapper">
                    <input type="file" name="avatar" class="form__file-input" accept="image/*">
                    <span class="form__file-input-button">Выбрать файл</span>
                    <span class="form__file-input-filename">Файл не выбран</span>
                </div>
                @error('avatar')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form__group">
                <label class="form__label">Имя (можно редактировать)</label>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form__input" required>
            </div>

            <div class="form__group">
                <label class="form__label">Описание (можно редактировать)</label>
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
                <input type="text" name="description" value="{{ old('description', $user->description) }}" class="form__input">
            </div>

            <div class="form__group">
                <button type="submit" class="form__submit-button button">Сохранить изменения</button>
            </div>
        </form>
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/editProfile.css') }}">
@endsection