@extends('base')

@section('title_base', 'Réserver une activité')
@section('reserver_active', 'active')

@section('content')

    <div class="res-container">
        <!-- Barre de recherche centrée -->
        <div class="search-bar" style="display: inline-flex; margin:auto;margin-top:10px; margin-bottom:20px; width:50%;">
            <input type="search" id="search-input" placeholder="Rechercher une entreprise par libellé..." 
                   style="width: 50%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; display:block; margin:auto; margin-right:0px;">
            <button type="button" class="btn btn-secondary my-2 my-sm-0" style="margin: auto; margin-left: 0px;"><i class="fa fa-search" id="rechercher" style="display:block;margin:auto;"></i></button>
        </div>
    </div>

    <div class="res-container">

        @foreach ($entreprises as $entreprise)
        @if($entreprise->publier && $entreprise->activites->count() > 0)
            <div class="res containerEntreprise" data-libelle="{{ Str::lower($entreprise->libelle) }}">
                <h2>{{ $entreprise->libelle }}</h2>
                <div class="entreprise">
                    <p style="overflow:auto; max-height:50px; min-height:50px;"><strong>Adresse :</strong> {{ $entreprise->adresse }}</p>
                    {{-- <p><strong>Métier :</strong> {{ $entreprise->metier }}</p> --}}
                    <p><strong>Description :</strong>
                    <div style="overflow:auto; max-height:150px; min-height:150px;">
                    {{ $entreprise->description }}</p>
                    </div>
                    <p><strong>Numéro de téléphone :</strong> {{ $entreprise->numTel }}</p>
                    @if ($entreprise->cheminImg && count(json_decode($entreprise->cheminImg)) > 1)
                        <div class="carousel">
                            <div class="swiper-container swiper{{ $entreprise->id }}">
                                <div class="swiper-wrapper">
                                    @foreach (json_decode($entreprise->cheminImg) as $img)
                                        <div class="swiper-slide">
                                            <img src="{{ $img }}" alt="{{ $img }}" height="200vh" width="200vh">
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
                        <img src="{{ json_decode($entreprise->cheminImg)[0] }}" alt="{{ $entreprise->libelle }}" height="200vh" width="200vh">
                    @else
                        <img src="https://www.map24.com/wp-content/uploads/2021/11/6784174_s.jpg" alt="{{ $entreprise->libelle }}" height="200vh" width="200vh">
                    @endif
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
                const entreprises = document.querySelectorAll('.containerEntreprise');

                entreprises.forEach(entreprise => {
                    const libelle = entreprise.getAttribute('data-libelle');
                    // Vérifie si le libellé commence par le texte saisi
                    if (libelle.startsWith(filter)) {
                        entreprise.style.display = 'block';
                    } else {
                        entreprise.style.display = 'none';
                    }
                });
            });

            $('#search-input').on('input', function() {
                const filter = $(this).val().toLowerCase();
                const entreprises = document.querySelectorAll('.containerEntreprise');

                if (filter === '') {
                    entreprises.forEach(entreprise => {
                        entreprise.style.display = 'block';
                    });
                    return;
                }
            });
        });
    </script>
@endsection
