@extends('layouts.app')

@section('title', __('Login user') )

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6">
            <!-- Bouton retour à l'accueil -->
            <a href="/" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left"></i>
            </a>

            <!-- Titre de la page -->
            <h3 class="text-center mb-4">{{ __('To Log In') }}</h3>

            <!-- Formulaire de connexion -->
            <form method="POST" action="{{ route('login') }}">
                @csrf <!-- Protection contre les attaques CSRF -->

                <!-- Champ Adresse Email -->
                <div class="mb-4">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           placeholder="{{ __('Email Address') }}">

                    @error('email') <!-- Gestion des erreurs de validation pour l'email -->
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Champ Mot de passe -->
                <div class="mb-4">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password" required autocomplete="current-password"
                           placeholder="{{ __('Enter your password') }}">

                    @error('password') <!-- Gestion des erreurs de validation pour le mot de passe -->
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Bouton de soumission -->
                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-dark">
                        {{ __('Log In') }}
                    </button>
                </div>

            <!-- Liens supplémentaires -->
            <div class="text-center">
                <p class="mb-1">
                    {{ __("Don't have an account?") }} <a href="{{ route('register.choose.account.type') }}">{{ __('To Register') }}</a>
                </p>
                <p>
                    {{ __('Forgot Your Password?') }} <a href="{{ route('password.request') }}">{{ __('Click here') }}</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
