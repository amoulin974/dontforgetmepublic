@extends('base')

@section('title', 'Mes entreprises')
@section('reserver_active', 'active')

@section('content')

    <div class="res-container">
        <!-- Barre de recherche centrée -->
        <div class="search-bar" style="display: flex; justify-content: center; margin-bottom: 20px;">
            <input type="text" id="search-input" placeholder="Rechercher une entreprise par libellé..." 
                   style="width: 50%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        @foreach ($entreprises as $entreprise)
        @if($entreprise->publier)
            <div class="res entreprise" data-libelle="{{ Str::lower($entreprise->libelle) }}">
                <h2>{{ $entreprise->libelle }}</h2>
                <div class="info">
                    <p><strong>Siren :</strong> {{ $entreprise->siren }}</p>
                    <p><strong>Adresse :</strong> {{ $entreprise->adresse }}</p>
                    <p><strong>Métier :</strong> {{ $entreprise->metier }}</p>
                    <p><strong>Description :</strong> {{ $entreprise->description }}</p>
                    <p><strong>Type :</strong> {{ $entreprise->type }}</p>
                    <p><strong>Numéro de téléphone :</strong> {{ $entreprise->numTel }}</p>
                    <p><strong>Email :</strong> {{ $entreprise->email }}</p>
                    @if ($entreprise->cheminImg && count(json_decode($entreprise->cheminImg)) > 1)
                        <div class="carousel">
                            <div class="swiper-container swiper{{ $entreprise->id }}">
                                <div class="swiper-wrapper">
                                    @foreach (json_decode($entreprise->cheminImg) as $img)
                                        <div class="swiper-slide">
                                            <img src="{{ $img }}" alt="{{ $img }}" height="300vh" width="300vh">
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
                        <img src="{{ json_decode($entreprise->cheminImg)[0] }}" alt="{{ $entreprise->libelle }}" height="300vh" width="300vh">
                    @else
                        <img src="https://www.map24.com/wp-content/uploads/2021/11/6784174_s.jpg" alt="{{ $entreprise->libelle }}" height="300vh" width="300vh">
                    @endif
                    <a class="secondary-button" href="{{ route('reservation.create', ['entreprise' => $entreprise->id]) }}">Réserver</a>
                </div>
            </div>
        @endif
        @endforeach
    </div>

    {{ $entreprises->links() }}

    <script>
        document.getElementById('search-input').addEventListener('input', function(e) {
            const filter = e.target.value.toLowerCase();
            const entreprises = document.querySelectorAll('.entreprise');

            entreprises.forEach(entreprise => {
                const libelle = entreprise.getAttribute('data-libelle');
                // Vérifie si le libellé commence par le texte saisi
                if (libelle.startsWith(filter)) {
                    entreprise.style.display = '';
                } else {
                    entreprise.style.display = 'none';
                }
            });
        });
    </script>
@endsection
