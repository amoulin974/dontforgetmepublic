@extends('layouts.app')

@section('title', 'Création de compte utilisateur')

@section('content')
<div class="container d-flex flex-column" style="min-height: 100vh; margin-top: 3rem; margin-bottom: 2rem;">
    <div class="d-flex justify-content-between align-items-center w-100" style="position: relative;">
        <a href="/" class="btn btn-outline-secondary mt-5 mb-4" style="position: absolute; left: 0;">
            <i class="bi bi-arrow-left"></i>
        </a>

 {{--        <div class="d-flex justify-content-center align-items-center w-100 mt-4 mb-4">
        @if (Route::currentRouteName() === 'register.company.register.user')
            <span class="me-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
            <span class="mx-2 bg-secondary rounded-circle" style="width: 12px; height: 12px;"></span>
            <span class="ms-2 bg-secondary rounded-circle" style="width: 12px; height: 12px;"></span>
        @endif
        </div> --}}
    </div>
    {{-- Titre --}}
    <h3 class="text-center mt-4 mb-4">{{ __('Register') }}</h3>

    @if (Route::currentRouteName() === 'register.company.register.user')
    <form method="POST" action="{{ route('register.user.store') }}">
    @else
    <form method="POST" action="{{ route('register') }}">
    @endif
        @csrf

        <div class="mt-1 row">
            {{-- Champ Nom  --}}
            <div class="mb-3 col-lg-6">
                <label for="nom" class="form-label">Nom</label>
                <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror" 
                        name="nom" value="{{ old('nom') }}" placeholder="Entrez votre nom" required>
                @error('nom')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{--  Champ Prénom --}} 
            <div class="mb-3 col-lg-6">
                <label for="prenom" class="form-label">Prénom</label>
                <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror" 
                        name="prenom" value="{{ old('prenom') }}" placeholder="Entrez votre prénom" required>
                @error('prenom')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Champ Numéro de Téléphone --}}
        <div class="mb-3">
            <label for="numTel" class="form-label">Numéro de téléphone (facultatif)</label>
            <input id="numTel" type="text" class="form-control @error('numTel') is-invalid @enderror" 
                    name="numTel" value="{{ old('numTel') }}" placeholder="Numéro de téléphone (** ** ** ** **)">
            @error('numTel')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Champ Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Adresse Email</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email') }}" placeholder="Entrez votre adresse Email" required>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Champ Mot de Passe --}}
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                    name="password" placeholder="Entrez votre mot de passe" required>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Champ Confirmation de Mot de Passe --}}
        <div class="mb-3">
            <label for="password-confirm" class="form-label">Confirmez le mot de passe</label>
            <input id="password-confirm" type="password" class="form-control" 
                    name="password_confirmation" placeholder="Confirmez votre mot de passe" required>
        </div>

        {{-- Bouton Créer un compte --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-dark mt-3">
                Créer son compte
            </button>
        </div>

        {{-- Liens supplémentaires --}}
        <div class="text-center mt-3">
            <p class="mb-1">
                Vous avez déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
            </p>
        </div>
    </form>
</div>
@endsection
