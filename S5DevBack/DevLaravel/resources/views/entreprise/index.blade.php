@extends('base')

@section('title', 'Mes entreprises')
@section('entreprises_active', 'active')

@section('content')

    <div class="res-container">
        @foreach ($entreprises as $entreprise)
            <div class="res">
                <h2>{{ $entreprise->libelle }}</h2>
                @if ($entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->first() != null)
                    <h3>[ {{ $entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->first()->pivot->statut }} ]</h3>
                @endif
                @if ($entreprise->idCreateur == Auth::user()->id)
                    <h3 style="color:blue;">[ Créateur ]</h3>
                @endif
                <div class="info">
                    <p><strong>Siren :</strong> {{ $entreprise->siren }}</p>
                    <p><strong>Adresse :</strong> {{ $entreprise->adresse }}</p>
                    <p><strong>Métier :</strong> {{ $entreprise->metier }}</p>
                    <p><strong>Description :</strong> {{ $entreprise->description }}</p>
                    <p><strong>Type :</strong> {{ $entreprise->type }}</p>
                    <p><strong>Numéro de téléphone :</strong> {{ $entreprise->numTel }}</p>
                    <p><strong>email :</strong> {{ $entreprise->email }}</p>
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
                                    grapCursor: true,
                                    /* pagination: {
                                        el: '.swiperPag{{ $entreprise->id }}',
                                        clickable: true,
                                        type: 'bullets', // Utiliser des puces pour la pagination
                                        dynamicBullets: true, // Activer les puces dynamiques
                                    }, *//* 
                                    autoplay: {
                                        delay: 2500,
                                        disableOnInteraction: true,
                                    }, */
                                    loop: true,
                                });
                                
                                $('.swiperPag{{ $entreprise->id }}').on('mouseout', function() {
                                    $('.swiperPag{{ $entreprise->id }}').css('visibility', 'hidden');
                                });
                            });
                        </script>
                        </div>
                        @elseif(($entreprise->cheminImg && count(json_decode($entreprise->cheminImg)) == 1))
                            <img src="{{ json_decode($entreprise->cheminImg)[0] }}" alt="{{ $entreprise->libelle }}" height="300vh" width="300vh">
                        @else
                                <img src="https://www.map24.com/wp-content/uploads/2021/11/6784174_s.jpg" alt="{{ $entreprise->libelle }}" height="300vh" width="300vh">
                        @endif
                    @if($entreprise->publier)
                    <p><strong>Publié !</strong></p>
                    @endif
                    @if ($entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->first() != null)
                    <a href="{{ route('entreprise.show', $entreprise->id) }}" class="secondary-button">Voir Plus</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{ $entreprises -> links() }}
    
@endsection