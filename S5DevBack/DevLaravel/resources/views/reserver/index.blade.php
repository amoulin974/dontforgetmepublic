@extends('base')

@section('title_base', 'Réserver une activité')
@section('reserver_active', 'active')

@section('content')

    <div class="res-container container">
        <!-- Barre de recherche centrée -->
        <div class="searchbar-home">
            <input class="form-control mr-sm-2 d-block" id="search-input" type="search" placeholder="Rechercher une entreprise par libellé..." aria-label="Search">
            <button class="btn btn-secondary my-2 my-sm-0 d-block"><i class="bi bi-search"></i></button>
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
                                                    <img src="{{ $img }}" alt="{{ $img }}" height="150vh" width="150vh">
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
    </div>

    {{ $entreprises->links() }}

    <script>
        $(document).ready(function() {
            document.getElementById('search-input').addEventListener('input', function(e) {
                const filter = e.target.value.toLowerCase();
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
        });
    </script>
@endsection
