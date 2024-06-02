<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>{{ $title }}</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    </head>
    <body>
        <div class="container">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/book.png') }}" alt="Главная">
            </a>
            <h1 class="text-center">{{ $pagetitle }}</h1>

            @yield('content')

        </div>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </body>
</html>

