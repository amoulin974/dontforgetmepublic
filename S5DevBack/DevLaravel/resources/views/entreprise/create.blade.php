@extends('layouts.app')

@section('title', __('Create business2'))

@section('content')
<div class="container d-flex flex-column" style="min-height: 100vh; margin-bottom: 2rem;">
    <div class="d-flex justify-content-between align-items-center w-100" style="position: relative;">
        {{-- Points de progression --}}
        <div class="d-flex justify-content-center align-items-center w-100 mt-5 mb-5">
            <span class="mx-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
            <span class="mx-2 bg-secondary rounded-circle" style="width: 12px; height: 12px;"></span>
        </div>
    </div>

    {{-- Titre --}}
    <h3 class="text-center mb-3">{{__("Create your business' profile")}}</h3>

    <p class="text-center mb-3">{{__("Warning: Our platform isn't for healthcare professionals!")}}</p>

    <form method="POST" action="{{ route('entreprise.typeRdv') }}">
        @csrf

        {{-- Champ Nom Entreprise --}}
        <div class="row mt-2">
            <div class="col-lg-6 mb-3">
                <label for="nomEntreprise" class="form-label">{{__('Business name')}}</label>
                <input id="nomEntreprise" type="text" class="form-control @error('nomEntreprise') is-invalid @enderror" 
                        name="nomEntreprise" value="{{ old('nomEntreprise', session('company.nomEntreprise', '')) }}" placeholder="{{__('Enter business name')}}" required>
                @error('nom')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Champ SIREN --}}
            <div class="col-lg-6 mb-3">
                <label for="siren" class="form-label">{{__('SIREN number')}}</label>
                <input id="siren" type="text" class="form-control @error('siren') is-invalid @enderror" 
                        name="siren" value="{{ old('siren', session('company.siren', '')) }}" placeholder="{{__('Enter SIREN number')}}" required>
                @error('siren')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Champ Numéro de Téléphone --}}
        <div class="mb-3">
            <label for="numTel" class="form-label">{{__('Phone number')}}</label>
            <input id="numTel" type="text" class="form-control @error('numTel') is-invalid @enderror" 
                    name="numTel" value="{{ old('numTel', session('company.numTel', '')) }}" placeholder="{{__('Phone number')}} (** ** ** ** **)">
            @error('numTel')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Champ Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">{{__('Email Address')}}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email', session('company.email', '')) }}" placeholder="{{__('Enter your Email address')}}" required>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Champ Adresse --}}
        <div class="mb-3">
            <label for="rue" class="form-label">{{__('Address')}}</label>
            <input id="rue" type="text" class="form-control @error('rue') is-invalid @enderror" 
                    name="rue" value="{{ old('rue', session('company.rue', '')) }}" placeholder="{{__('Avenue')}}" required>
            @error('rue')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="codePostal" class="form-label">{{__('Postal code')}}</label>
                <input id="codePostal" type="text" class="form-control @error('codePostal') is-invalid @enderror" 
                        name="codePostal" value="{{ old('codePostal', session('company.codePostal', '')) }}" placeholder="{{__('Postal code')}}" required>
                @error('codePostal')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="ville" class="form-label">{{__('City')}}</label>
                <input id="ville" type="text" class="form-control @error('ville') is-invalid @enderror" 
                        name="ville" value="{{ old('ville', session('company.ville', '')) }}" placeholder="{{__('City')}}" required>
                @error('ville')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Bouton Créer un compte --}}
        <div class="d-grid mt-3">
            <button type="submit" class="btn btn-dark">
                {{__('Create business profile')}}
            </button>

            <a href="{{ route('clear.session') }}" class="btn btn-link mt-2">
                {{__('Return to home page')}}
            </a>
        </div>
    </form>
</div>
@endsection