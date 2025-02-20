<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @hasSection('title_base')
        <title>@yield('title_base')</title>
    @endif
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/hamburgers/1.1.3/hamburgers.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- JQuery -->

    <!-- Pour les carousels -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Pour les icônes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Pour les notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

    <!-- Pour le menu burger -->
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
                @guest
                @else
                <li class="nav-item"><a href="{{ route('reservation.index') }}" class="@yield('catalogue_active') nav-link"><i class="fa fa-book"></i> Mes Réservations</a></li>
                <li class="nav-item"><a href="{{ route('entreprise.indexUser') }}" class="@yield('entreprises_active') nav-link"><i class="fa fa-industry"></i> Mes Entreprises</a></li>
                @endguest
                <li class="nav-item"><a href="{{ route('reserver.index') }}" class="@yield('reserver_active') nav-link"><i class="fa fa-calendar-plus"></i> Réserver</a></li>
            </ul>
        </div>
    </nav>
    <script>
        $(document).ready(function() {
            var collapse = $('.navbar-toggler');
            var ogParam1 = document.documentElement.style.overflow;
            var ogParam2 = document.body.style.overflow;

            collapse.on('click', function () {
                if (collapse.hasClass('collapsed')) {
                    document.documentElement.style.overflow = 'hidden';
                    document.body.style.overflow = 'hidden';
                }
                else {
                    document.documentElement.style.overflow = ogParam1;
                    document.body.style.overflow = ogParam2;
                }
            });
        });
    </script>

  <div class="profile-info">
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
              {{ Auth::user()->prenom }}
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

<script>
function displaySuccess(message) {
    toastr.options = {
        "closeButton": true,
        "newestOnTop": true,
        "progressBar": true
    }
    toastr.success(message, 'Succès !');
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

$(document).ready(function() {
    @if (session('success'))
        displaySuccess("{{ session('success') }}");
    @elseif (session('error'))
            displayError("{{ session('error') }}");
    @endif

    const successMessage = localStorage.getItem('success');
    if (successMessage) {
        displaySuccess(successMessage);
        localStorage.removeItem('success'); // Supprimer le message de succès du stockage local
    }
});
</script>

    @yield('content')

<button id="bug-report-btn" class="bug-report-button">
    <i class="fa fa-bug"></i> Signaler un bug
</button>

<div id="bugReportModal" class="modal fade" tabindex="-1" aria-labelledby="bugReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bugReportModalLabel">Signaler un bug</h5>
            </div>
            <div class="modal-body">
                <form id="bugReportForm" method="POST" action="{{ route('bug.report') }}">
                    @csrf
                    <div class="form-group">
                        <label for="bugDescription">Description du bug :</label>
                        <textarea id="bugDescription" name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-secondary me-2" id="cancel-bug-report">Annuler</button>
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#bug-report-btn").click(function () {
            $("#bugDescription").val('');
            $("#bugReportModal").modal("show");
        });

        $("#cancel-bug-report").click(function () {
            $("#bugDescription").val('');
            $("#bugReportModal").modal("hide");
        });
    });
</script>
</body>
</html>
