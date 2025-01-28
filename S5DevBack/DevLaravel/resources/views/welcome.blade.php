@extends('base')

@section('home_active', 'active')
@section('title_base', 'Don\'t Forget Me')


@section('content')
    <div class="container" style="margin-top:0px">
        <img class="logo-home" src="{{ asset('favicon.ico') }}" alt="Logo">
        <div class="searchbar-home">
            <input class="form-control mr-sm-2 d-block" id="search-input" type="search" placeholder="Rechercher une entreprise par libellé..." aria-label="Search">
            <button class="btn btn-secondary my-2 my-sm-0 d-block" id="rechercher"><i class="bi bi-search"></i></button>
        </div>
        <div class="container">
            @foreach ($entreprises as $entreprise)
            @if($entreprise->publier && $entreprise->activites->count() > 0)
                <div class="row container-entreprise" data-libelle="{{ Str::lower($entreprise->libelle) }}">
                    <div class="col-md-3 header-entreprise">
                    @if ($entreprise->cheminImg && count(json_decode($entreprise->cheminImg)) > 1)
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
                        @elseif($entreprise->cheminImg && count(json_decode($entreprise->cheminImg)) == 1)
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
