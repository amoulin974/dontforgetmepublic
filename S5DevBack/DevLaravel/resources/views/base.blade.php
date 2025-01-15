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

    <!-- Pour les icones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <button class="navbar-toggler custom-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse full-page-menu" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="/" class="@yield('home_active') nav-link"><i class="fa fa-home"></i> Accueil</a></li>
                <li class="nav-item"><a href="{{ route('reservation.index') }}" class="@yield('catalogue_active') nav-link"><i class="fa fa-book"></i> Mes Réservations</a></li>
                <li class="nav-item"><a href="{{ route('entreprise.indexUser') }}" class="@yield('entreprises_active') nav-link"><i class="fa fa-industry"></i> Mes Entreprises</a></li>
                @guest
                @else
                  <li class="nav-item"><a href="{{ route('parametrage.index') }}" class="@yield('parametrage_active') nav-link"><i class="fa fa-calendar"></i> Paramétrer vos plannings</a></li>
                @endguest
                <li class="nav-item"><a href="{{ route('reserver.index') }}" class="@yield('reserver_active') nav-link"><i class="fa fa-calendar-plus"></i> Réserver</a></li>
            </ul>
        </div>
    </nav>
    {{-- <script>
        $(document).ready(function() {
            var collapse = $('#navbarNav');

            collapse.on('click', function () {
                if (collapse.hasClass('show')) {
                    collapse.removeClass('show');
                }
            });
        });
    </script> --}}

  <div class="profileInfo">
    @guest
            @if (Route::has('login'))
                    
                    <a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> {{ __('Login') }}</a>
            @endif

            @if (Route::has('register'))
                    
                    <a class="nav-link" href="{{ route('register.choose.account.type') }}"><i class="fa fa-user-plus"></i> {{ __('Register') }}</a>
            @endif
        @else
            <a class="nameProfil @yield('profile_active')" href="{{ route('profile.index') }}">
              <i class="fa fa-user"></i>
              {{ Auth::user()->nom }}
            </a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                  {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
              @csrf
            </form>
      @endguest
    <a href="{{ route('home') }}" class="logo">
      <img src="{{ asset('favicon.ico') }}" alt="Logo">
    </a>
  </div>

    
</header>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

{{--
 <!DOCTYPE html>
 <html lang="fr">
 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>@yield('title')</title>
     
     <!-- Feuilles de style, scripts, etc. -->
     <link rel="stylesheet" href="{{ asset('css/base.css') }}">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/hamburgers/1.1.3/hamburgers.min.css">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 
     <!-- Swiper, Bootstrap… -->
     <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
     <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
 </head>
 <body>
 
 <header>
     <!-- Bouton hamburger (mobile) -->
     <button class="hamburger hamburger--spin" type="button" id="menuToggle">
         <span class="hamburger-box">
             <span class="hamburger-inner"></span>
         </span>
     </button>
 
     <!-- Logo -->
     <a href="{{ route('home') }}" class="logo">
         <img src="{{ asset('favicon.ico') }}" alt="Logo">
     </a>
 
     <!-- Menu Desktop -->
     <nav class="menu-desktop">
         <ul>
             <li>
                 <a href="/" class="@yield('home_active')">
                     <i class="fa fa-home"></i> Accueil
                 </a>
             </li>
             <li>
                 <a href="{{ route('reservation.index') }}" class="@yield('catalogue_active')">
                     <i class="fa fa-book"></i> Mes Réservations
                 </a>
             </li>
             <li>
                 <a href="{{ route('entreprise.indexUser') }}" class="@yield('entreprises_active')">
                     <i class="fa fa-industry"></i> Mes Entreprises
                 </a>
             </li>
             @guest
             @else
                 <li>
                     <a href="{{ route('parametrage.index') }}" class="@yield('parametrage_active')">
                         <i class="fa fa-calendar"></i> Paramétrer vos plannings
                     </a>
                 </li>
             @endguest
             <li>
                 <a href="{{ route('reserver.index') }}" class="@yield('reserver_active')">
                     <i class="fa fa-calendar-plus"></i> Réserver
                 </a>
             </li>
         </ul>
     </nav>
 
     <!-- Profil (auth) -->
     <div class="profileInfo">
         @guest
             @if (Route::has('login'))
                 <a href="{{ route('login') }}">
                     <i class="bi bi-box-arrow-in-right"></i> {{ __('Login') }}
                 </a>
             @endif
 
             @if (Route::has('register'))
                 <a href="{{ route('register.choose.account.type') }}">
                     <i class="fa fa-user-plus"></i> {{ __('Register') }}
                 </a>
             @endif
         @else
             <a class="nameProfil @yield('profile_active')" href="{{ route('profile.index') }}">
                 <i class="fa fa-user"></i>
                 {{ Auth::user()->nom }}
             </a>
             <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                 <i class="bi bi-box-arrow-right"></i>
                 {{ __('Logout') }}
             </a>
             <form id="logout-form" action="{{ route('logout') }}" method="POST">
                 @csrf
             </form>
         @endguest
     </div>
 
     <!-- Menu latéral (mobile) -->
     <nav id="mobileMenu">
         <ul>
             <li>
                 <a href="/" class="@yield('home_active')">
                     <i class="fa fa-home"></i> Accueil
                 </a>
             </li>
             <li>
                 <a href="{{ route('reservation.index') }}" class="@yield('catalogue_active')">
                     <i class="fa fa-book"></i> Mes Réservations
                 </a>
             </li>
             <li>
                 <a href="{{ route('entreprise.indexUser') }}" class="@yield('entreprises_active')">
                     <i class="fa fa-industry"></i> Mes Entreprises
                 </a>
             </li>
             @guest
             @else
                 <li>
                     <a href="{{ route('parametrage.index') }}" class="@yield('parametrage_active')">
                         <i class="fa fa-calendar"></i> Paramétrer vos plannings
                     </a>
                 </li>
             @endguest
             <li>
                 <a href="{{ route('reserver.index') }}" class="@yield('reserver_active')">
                     <i class="fa fa-calendar-plus"></i> Réserver
                 </a>
             </li>
         </ul>
     </nav>
 </header>
 
 <!-- Contenu dynamique -->
 <main>
     @yield('content')
 </main>
 
 <!-- Scripts -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
 <script>
     // Gestion hamburger + menu latéral
     const menuToggle = document.getElementById('menuToggle');
     const mobileMenu = document.getElementById('mobileMenu');
 
     menuToggle.addEventListener('click', () => {
         mobileMenu.classList.toggle('open');
         menuToggle.classList.toggle('is-active');
     });
 </script>
 
 <!-- Toastr messages -->
 <script>
 function displaySuccess(message) {
     toastr.options = {
         closeButton: true,
         newestOnTop: true,
         progressBar: true
     }
     toastr.success(message, 'Succès !');
 }
 function displayError(message) {
     toastr.options = {
         closeButton: true,
         newestOnTop: true,
         progressBar: true
     }
     toastr.error(message, 'Erreur !');
 }
 function displayMessage(message) {
     toastr.options = {
         closeButton: true,
         newestOnTop: true,
         progressBar: true
     }
     toastr.info(message, 'Information :');
 }
 function displayWarning(message) {
     toastr.options = {
         closeButton: true,
         newestOnTop: true,
         progressBar: true
     }
     toastr.warning(message, 'Attention...');
 }
 function displayErrorWithButton(message) {
     toastr.options = {
         closeButton: true,
         newestOnTop: true,
         progressBar: true
     }
     toastr.error(message, 'Erreur !', {
         timeOut: 0,
         extendedTimeOut: 0
     });
 }
 
 @if (session('success'))
     toastr.success("{{ session('success') }}");
 @elseif (session('error'))
     toastr.error("{{ session('error') }}");
 @endif
 </script>
 
 </body>
 </html>
 --}}