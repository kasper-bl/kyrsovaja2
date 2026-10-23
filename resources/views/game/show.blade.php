@extends('layouts.main')

@section('title', $game->title)

@section('content')
    <section class="game-cart wrapper">
        <div class="game-cart__content">
            <div class="game-cart__category">
                <p>
                    @if ($game->difficulty === 'easy')
                        Лёгкая
                    @elseif ($game->difficulty === 'normal')
                        Средняя
                    @else
                        Сложная
                    @endif
                </p>
            </div>
            <h2>{{ $game->title }}</h2>
            <h3>
                @foreach ($game->categories as $category)
                    {{ $category->name }}@if (!$loop->last), @endif
                @endforeach
            </h3>
            <p>{{ $game->description }}</p>

            @if ($game->rules_pdf)
                <a href="{{ asset($game->rules_pdf) }}" download>Читать правила</a>
            @endif

            <div class="game-cart__info">
                <div class="info__badge">
                    <div class="info__content">{{ $game->min_age }}+</div>
                    <p>Возраст</p>
                </div>
                <div class="info__badge">
                    <div class="info__content">{{ $game->duration_minutes }}м</div>
                    <p>Время игры</p>
                </div>
                <div class="info__badge">
                    <div class="info__content">{{ $game->min_players }}-{{ $game->max_players }}</div>
                    <p>Кол-во игроков</p>
                </div>
            </div>
        </div>

        <div class="game-cart__image-wrapper">
            <img src="{{ asset($game->image) }}" alt="{{ $game->title }}">
        </div>
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/gamePage.css') }}">
@endsection