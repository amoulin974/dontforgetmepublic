<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="/" class="@yield('home_active')">Accueil</a></li>
            <li><a href="{{ route('reservation.index') }}" class="@yield('catalogue_active')">Réservation</a></li>
        </ul>
    </nav>
    <a href="/" class="logo">
        <img src="{{ asset('favicon.ico') }}" alt="Logo">
    </a>
</header>

<div class="container">  
    @yield('content')
</div>

</body>
</html>
