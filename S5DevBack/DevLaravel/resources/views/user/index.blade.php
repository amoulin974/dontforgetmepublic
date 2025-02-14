@extends('base')

@section('title_base', Auth::user()->nom . ' ' . Auth::user()->prenom)
@section('profile_active', 'active')

@section('content')

<div class="container">
    <div class="header-profile">
        <h1 >{{__('Your profile')}}</h1>
        <br/>
    </div>
    <div class="container-entreprise">
    <div class="entreprise" id="profil">
        <h2>{{ $utilisateur->nom }} {{ $utilisateur->prenom }}</h2>
        <p><strong>{{__('Email Address')}} : </strong>{{ $utilisateur->email }}</p>
        <p><strong>{{__('Phone number')}} : </strong>{{ $utilisateur->numTel }}</p>
        <p><strong>{{__('Default notification')}} : </strong>{{ $utilisateur->typeNotif }}</p>
        <p><strong>{{__('Default delay until notification')}} : </strong>{{ $utilisateur->delaiAvantNotif }}</p>
        @if ($utilisateur->superadmin)
            <h4><strong>Superadmin</strong></h4>
        @endif
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">{{__('Edit profile')}}</a>
    </div>
    <a class="btn btn-primary" href="{{ route('entreprise.create') }}" style="margin:auto;"><i class="fa fa-plus"></i> {{__('Create business')}}</a>
    @if($entreprises->count() > 0)
    <h3 style="margin-top: 2%;">{{__('Your businesses')}} :</h3>
    <div style="overflow: auto; width:100%; max-height: 50%;">
        @foreach ($entreprises as $entreprise)
        <div class="entreprise">
            <h4>{{ $entreprise->libelle }}</h4>
            <div class="d-flex flex-wrap">
                <div class="col-md-8">
                    <p><strong>{{__('Address')}} : </strong>{{ $entreprise->adresse }}</p>
                    <p><strong>{{__('Description')}} :</strong> {{ $entreprise->description }}</p>
                </div>
                <div class="col-md-4 activity-button">
                    <a class="btn btn-primary" href="{{ route('entreprise.show', $entreprise->id) }}"><i class="fa fa-eye"></i> {{__('More')}}</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    </div>
<div>

@endsection