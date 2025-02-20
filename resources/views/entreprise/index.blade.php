@extends('base')

@section('title_base', __('My businesses'))
@section('entreprises_active', 'active')

@section('content')

    <div class="res-container">
        @if($entreprises->count() == 0)
        <br>
        <br><br>
        <br>
            <h3>{{__("You haven't worked for, been invited to or created any business yet")}}</h3>
        @else
        @foreach ($entreprises as $entreprise)
        <div class="col-md-6 col-xl-4">
            <div class="res">
                <h2 style="text-align: center">{{ $entreprise->libelle }}</h2>
                @if ($entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->first() != null)
                    <h4>[ {{ $entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->first()->pivot->statut }} ]</h4>
                @endif
                @if ($entreprise->idCreateur == Auth::user()->id)
                    <h5 style="color:blue;">[ {{__('Creator')}} ]</h5>
                @endif
                <div class="info">
                    <p><strong>{{__('SIREN')}} :</strong> {{ $entreprise->siren }}</p>
                    <p><strong>{{__('Address')}} :</strong> {{ $entreprise->adresse }}</p>
                    <p><strong>{{__('Job')}} :</strong> {{ $entreprise->metier }}</p>
                    <p><strong>{{__('Description')}} :</strong> {{ $entreprise->description }}</p>
                    <p><strong>{{__('Phone number')}} :</strong> {{ $entreprise->numTel }}</p>
                    <p><strong>{{__('Email Address')}} :</strong> {{ $entreprise->email }}</p>
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
                                new Swiper('.swiper{{ $entreprise->id }}', {
                                    slidesPerView: 1,
                                    spaceBetween: 10,
                                    grapCursor: true,
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
                    <p class="text-center"><i><strong>{{__('Published!')}}</strong></i></p>
                    @endif
                    @if($entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->wherePivot('statut','Invité')->count() > 0)
                        <p style="text-align: center"><i>{{__("You're invited to this business.")}}</i></p>
                    @endif
                    @if($entreprise->idCreateur == Auth::user()->id && $entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->wherePivot('statut','Admin')->first() == null)
                    <a class="btn btn-primary" href="{{ route('entreprise.services.index', ['entreprise' => $entreprise->id]) }}" style="display:block;margin-left:auto;margin-right:auto;">{{__("Create your first activity")}}</a>
                    @else
                    <a href="{{ route('entreprise.show', $entreprise->id) }}" class="secondary-button" style="display:block;margin-left:auto;margin-right:auto;">{{__('More')}}</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    {{ $entreprises -> links() }}

@endsection
