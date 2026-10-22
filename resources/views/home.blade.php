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

            {{-- Поиск и сортировка (пока без логики) --}}
            <div class="catalog__search-container">
                <div class="catalog__searth-backgroung">
                    <form class="catalog__search">
                        <input type="text" placeholder="Введите название игры...">
                        <button class="catalog__button button">Найти игру</button>
                    </form>
                </div>
                <div class="catalog__sort">
                    <div class="sort__list">
                        <div class="sort__title">
                            <p>Сортировка: Название</p>
                            <img src="{{ asset('img/icon/arrow_sort.svg') }}" alt="arrow">
                        </div>
                        <div class="sort__options">
                            <p>Сложность</p>
                            <p>Время игры</p>
                            <p>Возраст</p>
                            <p>Название</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="catalog__inner">

                {{-- Фильтры (пока без логики) --}}
                <div class="catalog__filters">
                    <div class="filters__content">
                        <h3>Тип</h3>
                        <div class="filters__category">
                            <p>Стратегия</p>
                            <p>(10)</p>
                        </div>
                        <div class="filters__category">
                            <p>Экономические</p>
                            <p>(15)</p>
                        </div>
                        <div class="filters__category">
                            <p>Коммуникативные</p>
                            <p>(19)</p>
                        </div>
                        <div class="filters__category">
                            <p>Логические</p>
                            <p>(5)</p>
                        </div>
                        <div class="filters__category">
                            <p>Ролевые</p>
                            <p>(13)</p>
                        </div>
                        <div class="filters__category">
                            <p>Викторины</p>
                            <p>(7)</p>
                        </div>
                        <div class="filters__category">
                            <p>Словесные</p>
                            <p>(18)</p>
                        </div>
                        <h3>Кол-во игроков</h3>
                        <div class="catalog__slider">
                            <div class="slider__container">
                                <div class="slider__track"></div>
                                <div class="slider__handle slider__handle-left"></div>
                                <div class="slider__handle slider__handle-right"></div>
                            </div>
                            <p>Игроки: <span>2-6</span></p>
                        </div>

                        <h3>Возраст</h3>
                        <div class="filters__age">
                            <p>3+</p>
                            <p>10+</p>
                            <p>16+</p>
                            <p>6+</p>
                            <p>14+</p>
                            <p>18+</p>
                        </div>
                        <h3>Сложность</h3>
                        <div class="filters__complexity">
                            <div>
                                <p>Лёгкие</p>
                                <p>(13)</p>
                            </div>
                            <div>
                                <p>Средние</p>
                                <p>(7)</p>
                            </div>
                            <div>
                                <p>Сложные</p>
                                <p>(18)</p>
                            </div>
                        </div>
                    </div>
                    <a class="button" href="">Применить</a>
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