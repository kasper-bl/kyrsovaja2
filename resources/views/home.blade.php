@extends('layouts.main')

@section('title', 'GameGrad')

@section('content')

    {{-- Главный баннер --}}
    <section class="main-banner">
        <div class="main-banner__inner wrapper">
            <div class="main-banner__text">
                <p class="blue-text">Мир в коробке</p>
                <h2>НАСТОЛЬНЫЕ ИГРЫ ДЛЯ ВАШЕЙ КОМПАНИИ</h2>
                <p>Собирайте друзей, раскрывайте коробки и погружайтесь в мир незабываемых приключений, стратегических баталий и веселья, которое объединяет.</p>
                <a href="#catalog" class="button">Подобрать игру</a>
            </div>
            <img src="{{ asset('img/banners/main-banner.png') }}" alt="cubs">
        </div>
    </section>

    {{-- Каталог --}}
    <section class="catalog wrapper" id="catalog">
        <div class="catalog__search-inner">

            {{-- Поиск и сортировка --}}
            <div class="catalog__search-container">
                <div class="catalog__searth-backgroung">
                    <form class="catalog__search" id="search-form" method="GET" action="{{ route('home') }}">
                        <input type="text" name="search" placeholder="Введите название игры..." value="{{ request('search') }}">
                        <button type="submit" class="catalog__button button">Найти игру</button>
                    </form>
                </div>
                <div class="catalog__sort">
                    <div class="sort__list">
                        <div class="sort__title">
                            @php
                                $sortLabels = [
                                    'difficulty' => 'Сложность',
                                    'duration' => 'Время игры',
                                    'age' => 'Возраст',
                                    'title' => 'Название',
                                ];
                                $currentSort = request('sort', 'title');
                            @endphp
                            <p>Сортировка: {{ $sortLabels[$currentSort] ?? 'Название' }}</p>
                            <img src="{{ asset('img/icon/arrow_sort.svg') }}" alt="arrow">
                        </div>
                        <div class="sort__options">
                            <p data-sort="difficulty">Сложность</p>
                            <p data-sort="duration">Время игры</p>
                            <p data-sort="age">Возраст</p>
                            <p data-sort="title">Название</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="catalog__inner">

                {{-- Фильтры (пока без логики) --}}
                <div class="catalog__filters">
                    <form id="filter-form" class="filters__content">

                        {{-- Тип --}}
                        <h3>Тип</h3>
                        @foreach ($categories as $category)
                            <div class="filters__category">
                                <label class="filter-item">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                        @if (in_array($category->id, (array) request('categories', []))) checked @endif>
                                    <span>{{ $category->name }}</span>
                                </label>
                            </div>
                        @endforeach

                        {{-- Кол-во игроков (слайдер) --}}
                        <h3>Кол-во игроков</h3>
                        <div class="catalog__slider">
                            <div class="slider__container">
                                <div class="slider__track" id="slider-track"></div>
                                <div class="slider__handle slider__handle-left" id="slider-left"></div>
                                <div class="slider__handle slider__handle-right" id="slider-right"></div>
                            </div>
                            <p>Игроки: <span id="slider-label">2-6</span></p>
                            <input type="hidden" name="players_min" id="players-min" value="{{ request('players_min', 2) }}">
                            <input type="hidden" name="players_max" id="players-max" value="{{ request('players_max', 6) }}">
                        </div>

                        {{-- Возраст --}}
                        <h3>Возраст</h3>
                        <div class="filters__age">
                            @foreach ([3, 6, 10, 14, 16, 18] as $age)
                                <label class="filter-item">
                                    <input type="radio" name="age" value="{{ $age }}"
                                        @if (request('age') == $age) checked @endif>
                                    <span>{{ $age }}+</span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Сложность --}}
                        <h3>Сложность</h3>
                        <div class="filters__complexity">
                            <div>
                                <label class="filter-item">
                                    <input type="checkbox" name="difficulty[]" value="easy"
                                        @if (in_array('easy', (array) request('difficulty', []))) checked @endif>
                                    <span>Лёгкие</span>
                                </label>
                            </div>
                            <div>
                                <label class="filter-item">
                                    <input type="checkbox" name="difficulty[]" value="normal"
                                        @if (in_array('normal', (array) request('difficulty', []))) checked @endif>
                                    <span>Средние</span>
                                </label>
                            </div>
                            <div>
                                <label class="filter-item">
                                    <input type="checkbox" name="difficulty[]" value="hard"
                                        @if (in_array('hard', (array) request('difficulty', []))) checked @endif>
                                    <span>Сложные</span>
                                </label>
                            </div>
                        </div>
                    </form>
                    <a class="button" id="apply-filters" href="#">Применить</a>
                </div>

                {{-- Контейнер, который будет обновляться через AJAX --}}
                <div id="catalog-results" style="flex: 1; display: flex; flex-direction: column; gap: 30px;">

                    {{-- Сетка карточек --}}
                    <div class="catalog__container">
                        @foreach ($games as $game)
                            <article class="catalog__cart">
                                <div class="card__bg-wrapper">
                                    <img src="{{ asset($game->image) }}" alt="{{ $game->title }}" class="card__bg">
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
                    @if ($games->hasPages())
                        <div class="catalog__pagination">
                            @for ($i = 1; $i <= $games->lastPage(); $i++)
                                @if ($i == $games->currentPage())
                                    <div class="pagination__item--active">
                                        <a href="{{ $games->url($i) }}"><p>{{ $i }}</p></a>
                                    </div>
                                @else
                                    <div class="pagination__item">
                                        <a href="{{ $games->url($i) }}"><p>{{ $i }}</p></a>
                                    </div>
                                @endif
                            @endfor

                            @if ($games->hasMorePages())
                                <a href="{{ $games->nextPageUrl() }}">
                                    <img src="{{ asset('img/icon/next.svg') }}" alt="next">
                                </a>
                            @endif

                            <a href="{{ $games->url($games->lastPage()) }}">
                                <img src="{{ asset('img/icon/end.svg') }}" alt="end">
                            </a>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </section>

    {{-- О нас --}}
    <section class="about-us" id="about-us">
        <div class="about-us__inner wrapper">
            <img src="{{ asset('img/banners/chess.png') }}" alt="chess">
            <div class="about-us__text">
                <h2>ВАШ ПРОВОДНИК В МИР НАСТОЛЬНЫХ ИГР</h2>
                <p class="blue-text">ИГРАЙТЕ С ДУШОЙ</p>
                <p>Мы живем миром настольных игр и знаем, что идеальная игра это та, что собирает друзей за одним столом, дарит смех и заставляет задуматься. Наша задача помочь вам не заблудиться в этом многообразии. Мы подберем игру именно под вашу компанию, настроение и желания, чтобы каждая партия становилась маленьким приключением.</p>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection