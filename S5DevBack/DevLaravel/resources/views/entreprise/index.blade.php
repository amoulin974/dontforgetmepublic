@extends('base')

@section('title_base', 'Mes entreprises')
@section('entreprises_active', 'active')

@section('content')

    <div class="res-container">
        @if($entreprises->count() == 0)
        <br>
        <br><br>
        <br>
            <h3>Vous ne travaillez, n'êtes invité ou n'avez créé aucune entreprise</h3>
        @else
        @foreach ($entreprises as $entreprise)
        <div class="col-md-6 col-xl-4">
            <div class="res">
                <h2 style="text-align: center">{{ $entreprise->libelle }}</h2>
                @if ($entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->first() != null)
                    <h4>[ {{ $entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->first()->pivot->statut }} ]</h4>
                @endif
                @if ($entreprise->idCreateur == Auth::user()->id)
                    <h5 style="color:blue;">[ Créateur ]</h5>
                @endif
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
                                        <img class="info-image" src="{{ $img }}" alt="{{ $img }}" height="250vh" width="250vh">
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
                            <img class="info-image" src="{{ json_decode($entreprise->cheminImg)[0] }}" alt="{{ $entreprise->libelle }}" height="250vh" width="250vh">
                        @else
                                <img class="info-image" src="https://www.map24.com/wp-content/uploads/2021/11/6784174_s.jpg" alt="{{ $entreprise->libelle }}" height="250vh" width="250vh">
                        @endif
                    @if($entreprise->publier)
                    <p class="text-center"><i><strong>Publié !</strong></i></p>
                    @endif
                    @if($entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->wherePivot('statut','Invité')->count() > 0)
                        <p style="text-align: center"><i>Vous êtes invités dans cette entreprise.</i></p>
                    @endif
                    @if($entreprise->idCreateur == Auth::user()->id && $entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->wherePivot('statut','Admin')->first() == null)
                    <a class="btn btn-primary" href="{{ route('entreprise.services.index', ['entreprise' => $entreprise->id]) }}" style="display:block;margin-left:auto;margin-right:auto;">Créer votre première activité</a>
                    @else
                    <a href="{{ route('entreprise.show', $entreprise->id) }}" class="secondary-button" style="display:block;margin-left:auto;margin-right:auto;">Voir plus</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    {{ $entreprises -> links() }}
    
@endsection