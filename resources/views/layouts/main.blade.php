<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/root.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('styles')
    <title>@yield('title', 'GameGrad')</title>
</head>
<body>
    <header class="header wrapper">
        <div class="header__logo">
            <a href="{{ url('/') }}"><img src="{{ asset('img/icon/logo.svg') }}" alt="logo"></a>
        </div>
        <div class="header__content">
            <nav class="header__nav">
                <a href="{{ url('/') }}#catalog">Каталог</a>
                <a href="{{ url('/') }}#about-us">О нас</a>
                <a href="{{ url('/') }}#footer">Контакты</a>
            </nav>
            <div class="header__button">
                @auth
                    <a href="{{ route('profile') }}" class="button">Профиль</a>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="button">Выход</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="button">Вход</a>
                    <a href="{{ route('register') }}" class="button">Регистрация</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer id="footer" class="footer wrapper">
        <div class="footer__form">
            <img src="{{ asset('img/icon/logo.svg') }}" alt="logo">
            
            <!-- НАЧАЛО ИСПРАВЛЕННОЙ ФОРМЫ -->
            @if(session('suggestion_success'))
                <div style="color: #4CAF50; margin-bottom: 10px; font-weight: bold;">
                    {{ session('suggestion_success') }}
                </div>
            @endif

            <form action="{{ route('suggestion.store') }}" method="POST">
                @csrf
                <input type="text" name="title" placeholder="Предложите свою игру" value="{{ old('title') }}" required>
                <button type="submit" class="button">Отправить</button>
            </form>
            @error('title')
                <div style="color: #ff4d4d; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
            @enderror
            <!-- КОНЕЦ ИСПРАВЛЕННОЙ ФОРМЫ -->

        </div>
        <div class="footer__soc-networks">
            <div class="footer__img">
                <a href="/"><img src="{{ asset('img/social-network/email.svg') }}" alt="email"></a>
                <a href="/"><img src="{{ asset('img/social-network/tg.svg') }}" alt="tg"></a>
                <a href="/"><img src="{{ asset('img/social-network/vk.svg') }}" alt="vk"></a>
            </div>
            <p> &copy; Gamegrad 2025 &reg; - Ваш гид в мире игр</p>
        </div>
    </footer>
    
    @yield('scripts')
</body>
</html>