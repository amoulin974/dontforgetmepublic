@extends('base')

@section('title', 'Mes entreprises')
@section('entreprises_active', 'active')

@section('content')

    <div class="res-container">
        @if($entreprises->count() == 0)
        <br>
        <br><br>
        <br>
            <h3>Vous ne travaillez ou n'avez créé aucune entreprise</h3>
        @else
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
                                        <img src="{{ $img }}" alt="{{ $img }}" height="250vh" width="250vh">
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
                            <img src="{{ json_decode($entreprise->cheminImg)[0] }}" alt="{{ $entreprise->libelle }}" height="250vh" width="250vh">
                        @else
                                <img src="https://www.map24.com/wp-content/uploads/2021/11/6784174_s.jpg" alt="{{ $entreprise->libelle }}" height="250vh" width="250vh">
                        @endif
                    @if($entreprise->publier)
                    <p><strong>Publié !</strong></p>
                    @endif
                    @if ($entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->wherePivot('statut','Admin')->first() != null)
                    <a href="{{ route('entreprise.show', $entreprise->id) }}" class="secondary-button">Voir plus</a>
                    @endif
                    {{-- @if ($entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->wherePivot('statut','Admin')->first() != null)
                        <a class="btn btn-primary" href="{{ route('entreprise.services.index', ['entreprise' => $entreprise->id]) }}">Paramétrer les plages</a>
                        <a class="btn btn-primary light" href="{{ route('parametrage.plage.idEntrepriseAsEmploye', ['entreprise' => $entreprise->id]) }}">Visualiser vos plages</a>
                        <a href="{{ route('entreprise.show', $entreprise->id) }}" class="secondary-button">Voir plus</a>
                    @elseif ($entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->wherePivot('statut','Employé')->first() != null)
                        <a class="btn btn-primary light" href="{{ route('parametrage.plage.idEntrepriseAsEmploye', ['entreprise' => $entreprise->id]) }}">Visualiser vos plages</a>
                    @endif --}}
                </div>
            </div>
        @endforeach
        @endif
    </div>

    {{ $entreprises -> links() }}
    
@endsection