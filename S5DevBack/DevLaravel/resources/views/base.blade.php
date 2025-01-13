<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/hamburgers/1.1.3/hamburgers.min.css">

    <!-- Pour les notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- JQuery -->

    <!-- Pour les carousel -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<header>
<nav>
        <ul>
            <li><a href="/" class="@yield('home_active')">Accueil</a></li>
            <li><a href="{{ route('reservation.index') }}" class="@yield('catalogue_active')">Réservations</a></li>
            <li><a href="{{ route('entreprise.indexUser') }}" class="@yield('entreprises_active')">Entreprises</a></li>
            <li><a href="{{ route('calendrier.index') }}" class="@yield('creneau_active')">Créneaux</a></li>
            @guest
            @else
              <li><a href="{{ route('parametrage.index') }}" class="@yield('parametrage_active')">Paramétrer vos plannings</a></li>
            @endguest
            <li><a href="{{ route('reserver.index') }}" class="@yield('reserver_active')">Réserver</a></li>
        </ul>
    </nav>

    {{-- <div class="burger-menu">
        <button class="hamburger hamburger--collapse" type="button">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button>
        <nav class="menu">
            <ul>
                <li><a href="/" class="@yield('home_active')">Accueil</a></li>
                <li><a href="{{ route('reservation.index') }}" class="@yield('catalogue_active')">Réservations</a></li>
                <li><a href="{{ route('entreprise.index') }}" class="@yield('entreprises_active')">Entreprises</a></li>
                <li><a href="{{ route('calendrier.index') }}" class="@yield('creneau_active')">Créneaux</a></li>
            </ul>
        </nav>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var burger = document.querySelector('.hamburger');
            var menu = document.querySelector('.menu');

            burger.addEventListener('click', function() {
                burger.classList.toggle('is-active');
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script> -->

    <!-- <div class="wrapper">
      <div class="item menu">
        <div class="linee linee1"></div>
        <div class="linee linee2"></div>
        <div class="linee linee3"></div>
      </div>
      <div class="item gallery">
        <div class="dot dot1"></div>
        <div class="dot dot2"></div>
        <div class="dot dot3"></div>
        <div class="dot dot4"></div>
        <div class="dot dot5"></div>
        <div class="dot dot6"></div>
      </div>
      <button class="item add">
        <div class="circle">
          <div class="close">
          <div class="line line1"></div>
          <div class="line line2"></div>
        </div>
        </div>
        <input type="search" placeholder="search" class="search" />
        
      </button>

      <div class="nav-items items1">
        <i class="fas fa-home"></i>
      </div>
      <div class="nav-items items2">
        <i class="fas fa-camera"></i>
      </div>
      <div class="nav-items items3">
        <i class="fas fa-folder"></i>
      </div>
      <div class="nav-items items4">
        <i class="fas fa-heart"></i>
      </div>
      <div class="box">
        <div class="box-line box-line1"></div>
        <div class="box-line box-line2"></div>
        <div class="box-line box-line3"></div>
        <div class="box-line box-line4"></div>
      </div>
    </div>

    <div class="effect"></div>

    <script>
    document.querySelector(".circle").addEventListener("click", () => {
        for (let i = 0; i <= 3; i++) {
          document
            .getElementsByClassName("nav-items")
            [i].classList.remove("show-menu");
          document
            .getElementsByClassName("box-line")
            [i].classList.remove("box-line-show");
        }
        document.querySelector(".box").classList.remove("box-show");
        document.querySelector(".add").classList.toggle("go");
        document.querySelector(".search").classList.toggle("search-focus");
        document.querySelector(".search").focus();
        document.querySelector(".circle").classList.toggle("color");
        document.querySelector(".line1").classList.toggle("move");
        document.querySelector(".line2").classList.toggle("mov");
        document.querySelector(".effect").classList.toggle("big");
      });
      /* menu */
      document.querySelector(".menu").addEventListener("click", () => {
        for (let i = 0; i <= 3; i++) {
          document.querySelector(".box").classList.remove("box-show");
          document
            .getElementsByClassName("nav-items")
            [i].classList.toggle("show-menu");
          document
            .getElementsByClassName("box-line")
            [i].classList.remove("box-line-show");
        }
      });
      /* box */
      document.querySelector(".gallery").addEventListener("click", () => {
        document.querySelector(".box").classList.toggle("box-show");
        for (let i = 0; i <= 3; i++) {
          document
            .getElementsByClassName("box-line")
            [i].classList.toggle("box-line-show");
          document
            .getElementsByClassName("nav-items")
            [i].classList.remove("show-menu");
        }
      });
    </script> --}}


    <div class="profileInfo">
    @guest
            @if (Route::has('login'))
                    <a href="{{ route('login') }}">{{ __('Login') }}</a>
            @endif

            @if (Route::has('register'))
                    <a class="nav-link" href="{{ route('register.choose.account.type') }}">{{ __('Register') }}</a>
            @endif
        @else
            <a class="nameProfil" href="#">{{-- href="{{ route('profil.index') }}" --}}
              {{ Auth::user()->nom }}
            </a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
              @csrf
            </form>
      @endguest
      <a href="/" class="logo">
        <img src="{{ asset('favicon.ico') }}" alt="Logo">
      </a>
      </div>

    
</header>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
{{-- <div class="card-header">{{ __('Dashboard') }}</div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{ __('You are logged in!') }}
        </div> --}}

<script>
function displaySuccess(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.success(message, 'Succés !');
}

function displayError(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.error(message, '! Erreur !');
}

function displayMessage(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.info(message, 'Information :');
}

function displayWarning(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.warning(message, 'Attention...');
}

function displayErrorWithButton(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.error(message, '! Erreur !', {
        timeOut: 0,
        extendedTimeOut: 0
    });
}
</script>

@if (session('success'))
    <script>
        toastr.success("{{ session('success') }}");
    </script>
@elseif (session('error'))
    <script>
        toastr.error("{{ session('error') }}");
    </script>
@endif
    @yield('content')
</div>

</body>
</html>
