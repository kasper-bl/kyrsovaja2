<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/root.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
    <title>@yield('title', 'Gamegrad')</title>
</head>
<body>
    <header class="header wrapper">
        <a href="{{ url('/') }}" class="button">Выход</a>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>