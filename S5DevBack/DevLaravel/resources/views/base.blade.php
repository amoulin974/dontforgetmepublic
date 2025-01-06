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
            <li><a href="{{ route('reservation.index') }}" class="@yield('catalogue_active')">Réservations</a></li>
            <li><a href="{{ route('entreprise.index') }}" class="@yield('entreprises_active')">Entreprises</a></li>
            <li><a href="{{ route('calendrier.index') }}" class="@yield('creneau_active')">Créneaux</a></li>
        </ul>
        @guest
        <ul class="idd">
            @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
            @endif

            @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
            @endif
        @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->nom }}
                </a>

                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
        @endguest
    </nav>
    <a href="/" class="logo">
        <img src="{{ asset('favicon.ico') }}" alt="Logo">
    </a>
</header>
<!-- <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div> -->
<div class="container">  
@if (session('status'))
    <div class="success-message" role="alert">
        {{ session('status') }}
    </div>
@endif
    @yield('content')
</div>

</body>
</html>
