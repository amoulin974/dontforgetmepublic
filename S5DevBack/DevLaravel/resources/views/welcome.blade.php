@extends('base')

@section('home_active', 'active')
@section('title_base', 'Don\'t Forget Me')


@section('content')
    <div class="container" style="margin-top:0px">
        <img class="logo-home" src="{{ asset('favicon.ico') }}" alt="Logo">
        <div class="searchbar-home">
            <input class="form-control mr-sm-2 d-block" id="search-input" type="search" placeholder="Rechercher une entreprise par libellé..." aria-label="Search">
            <button class="btn btn-secondary my-2 my-sm-0 d-block"><i class="bi bi-search"></i></button>
        {{-- <img src="{{ asset('favicon.ico') }}" alt="Logo" style="max-width: 50vh; max-height: 50vh; display:block; margin-top:0px; margin:auto;">
        <div style="display: inline-flex; width: 100%;">
        <input class="form-control mr-sm-2" id="search-input" style="display: block; margin-left:30%;" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-secondary my-2 my-sm-0" style="display: block;margin-right:30%;" id="rechercher"><i class="bi bi-search"></i></button> --}}
        </div>
        <div class="container">
            @foreach ($entreprises as $entreprise)
            @if($entreprise->publier && $entreprise->activites->count() > 0)
                <div class="row container-entreprise" data-libelle="{{ Str::lower($entreprise->libelle) }}">
                    <div class="col-md-3 header-entreprise">
                    {{-- @if ($entreprise->cheminImg && count(json_decode($entreprise->cheminImg)) > 1)
                            <div class="carousel" style="display: block; margin:auto;">
                                <div class="swiper-container swiper{{ $entreprise->id }}">
                                    <div class="swiper-wrapper">
                                        @foreach (json_decode($entreprise->cheminImg) as $img)
                                            <div class="swiper-slide">
                                                <img src="{{ $img }}" alt="{{ $img }}" height="100vh" width="100vh">
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="swiper-pagination swiperPag{{ $entreprise->id }}"></div>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var swiper = new Swiper('.swiper{{ $entreprise->id }}', {
                                            slidesPerView: 1,
                                            spaceBetween: 10,
                                            grabCursor: true,
                                            loop: true,
                                        });
                                    });
                                </script>
                            </div>
                        @elseif($entreprise->cheminImg && count(json_decode($entreprise->cheminImg)) == 1) --}}@if($entreprise->cheminImg)
                            <img src="{{ json_decode($entreprise->cheminImg)[0] }}" style="margin-block:auto;" alt="{{ $entreprise->libelle }}" height="150vh" width="150vh">
                        @else
                            <img src="https://www.map24.com/wp-content/uploads/2021/11/6784174_s.jpg" style="margin-block:auto;" alt="{{ $entreprise->libelle }}" height="150vh" width="150vh">
                        @endif
                    </div>
                    <div class="col-md-5 info-entreprise">
                        <h3>{{ $entreprise->libelle }}</h3>
                        <p>{{ $entreprise->adresse }}</p>
                        <div style="margin-bottom:15px;overflow:auto; max-height:14vh;">
                        <p style="margin-bottom: 0px">{{ $entreprise->description }}</p>
                        </div>
                        <p style="margin-bottom: 0px"><strong>Email :</strong> {{ $entreprise->email }}</p>
                        <p style="margin-bottom: 0px"><strong>Numéro de téléphone :</strong> {{ $entreprise->numTel }}</p>
                    </div> 
                    <div class="col-md-4 text-center" style="margin-block: auto">
                        <a class="secondary-button" href="{{ route('entreprise.activites', ['entreprise' => $entreprise->id]) }}" id="reserver">Réserver une activité</a>
                    </div>
                </div>
            @endif
            @endforeach
        </div>

        {{ $entreprises->links() }}

    <script>
        $(document).ready(function() {
            $('#rechercher').click(function() {
                const filter = $("#search-input").val().toLowerCase();
                const entreprises = document.querySelectorAll('.container-entreprise');

                entreprises.forEach(entreprise => {
                    const libelle = entreprise.getAttribute('data-libelle');
                    // Vérifie si le libellé commence par le texte saisi
                    if (libelle.startsWith(filter)) {
                        entreprise.style.display = 'flex';
                    } else {
                        entreprise.style.display = 'none';
                    }
                });
            });

            $('#search-input').on('input', function() {
                const filter = $(this).val().toLowerCase();
                const entreprises = document.querySelectorAll('.container-entreprise');

                if (filter === '') {
                    entreprises.forEach(entreprise => {
                        entreprise.style.display = 'flex';
                    });
                    return;
                }
            });
        });
        
    </script>
    </div>
@endsection

{{--
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.dark\:text-gray-500{--tw-text-opacity:1;color:#6b7280;color:rgba(107,114,128,var(--tw-text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <header>
            <div></div>

            <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
                @if (Route::has('login'))
                    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                        @auth
                            <a href="{{ url('/home') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Home</a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">SIGN UP</a>
                            @endif

                            <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">LOGIN</a>

                        @endauth
                    </div>
                @endif
        </header>
    </body>
</html>
--}}
