@extends('layouts.main')

@section('title', 'Профиль')

@section('content')
    {{-- Информация о пользователе --}}
    <section class="user-profile wrapper">
        <div class="user-profile__inner">
            @if ($user->avatar)
                <img src="{{ asset($user->avatar) }}" alt="Аватар">
            @else
                <img src="{{ asset('img/user_photo_default.png') }}" alt="Аватар">
            @endif

            <div class="user-profile__info">
                <h2>{{ $user->name }}</h2>
                <a href="{{ route('profile.edit') }}" class="user-profile__edit-link">Редактировать профиль</a>
                <div class="user-profile__bio">
                    <label class="user-profile__bio-label">Описание</label>
                    <div class="user-profile__description">
                        <p>{{ $user->description ?? 'Описание пока не заполнено' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Избранные игры --}}
    <section class="favorit-games wrapper">
        <div class="favorit-games__tabs">
            <div class="favorit-games__variant">
                <p class="favorit-games__tabs--active">ИЗБРАННОЕ</p>
            </div>
        </div>

        <div id="catalog-results" class="catalog-results">
            @if ($favorites->count() > 0)
                <div class="favorit-games__content">
                    @foreach ($favorites as $game)
                        <article class="catalog__cart">
                            <div class="card__bg-wrapper">
                                <a href="{{ route('game.show', $game->id) }}">
                                    <img src="{{ asset($game->image) }}" alt="{{ $game->title }}" class="card__bg">
                                </a>
                            </div>
                            <div class="cart__top">
                                <img
                                    src="{{ asset('img/icon/close.svg') }}"
                                    alt="Удалить из избранного"
                                    class="remove-favorite"
                                    data-game-id="{{ $game->id }}"
                                    title="Удалить из избранного"
                                >
                            </div>
                            <div class="cart__content">
                                <h2>{{ $game->title }}</h2>
                                <p class="cart__difficulty-{{ $game->difficulty }}">Уровень сложности</p>
                                <div class="cart__progress-bar">
                                    <div class="cart__progress-{{ $game->difficulty }}"></div>
                                </div>
                                <div class="cart__players">
                                    <img src="{{ asset('img/icon/user_icon.svg') }}" alt="user_icon">
                                    <p>{{ $game->min_players }} - {{ $game->max_players }}</p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Пагинация --}}
                @if ($favorites->hasPages())
                    <div class="catalog__pagination">
                        @for ($i = 1; $i <= $favorites->lastPage(); $i++)
                            @if ($i == $favorites->currentPage())
                                <div class="pagination__item--active">
                                    <a href="{{ $favorites->url($i) }}"><p>{{ $i }}</p></a>
                                </div>
                            @else
                                <div class="pagination__item">
                                    <a href="{{ $favorites->url($i) }}"><p>{{ $i }}</p></a>
                                </div>
                            @endif
                        @endfor

                        @if ($favorites->hasMorePages())
                            <a href="{{ $favorites->nextPageUrl() }}">
                                <img src="{{ asset('img/icon/next.svg') }}" alt="next">
                            </a>
                        @endif
                        <a href="{{ $favorites->url($favorites->lastPage()) }}">
                            <img src="{{ asset('img/icon/end.svg') }}" alt="end">
                        </a>
                    </div>
                @endif
            @else
                <p class="empty-favorites">
                    У вас пока нет избранных игр. Добавляйте их со страницы игры!
                </p>
            @endif
        </div>
    </section>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection