@extends('layouts.app')

@section('title', __('Create user account'))

@section('content')
    <div class="container d-flex flex-column" style="min-height: 100vh; margin-top: 3rem; margin-bottom: 2rem;">
        <!-- Section du bouton retour -->
        <div class="d-flex justify-content-between align-items-center w-100" style="position: relative;">
            <a href="/" class="btn btn-outline-secondary mt-5 mb-4" style="position: absolute; left: 0;">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>

        <!-- Titre principal -->
        <h3 class="text-center mt-4 mb-4">{{ __('Register') }}</h3>

        <!-- Condition pour définir la route selon le contexte -->
        @if (Route::currentRouteName() === 'register.company.register.user')
            <form method="POST" action="{{ route('register.user.store') }}">
                @else
                    <form method="POST" action="{{ route('register') }}">
                        @endif
                        @csrf <!-- Protection contre les attaques CSRF -->

                        <div class="mt-1 row">
                            <!-- Champ Nom -->
                            <div class="mb-3 col-lg-6">
                                <label for="nom" class="form-label">{{ __('Last name') }}</label>
                                <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror"
                                       name="nom" value="{{ old('nom') }}" placeholder="{{ __('Enter your last name') }}" required>
                                @error('nom')
                                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                                @enderror
                            </div>

                            <!-- Champ Prénom -->
                            <div class="mb-3 col-lg-6">
                                <label for="prenom" class="form-label">{{ __('First name') }}</label>
                                <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror"
                                       name="prenom" value="{{ old('prenom') }}" placeholder="{{ __('Enter your first name') }}" required>
                                @error('prenom')
                                <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Champ Numéro de téléphone (optionnel) -->
                        <div class="mb-3">
                            <label for="numTel" class="form-label">{{ __('Phone number') }} ({{ __('optional') }})</label>
                            <input id="numTel" type="text" class="form-control @error('numTel') is-invalid @enderror"
                                   name="numTel" value="{{ old('numTel') }}" placeholder="{{ __('Phone number') }} (** ** ** ** **)">
                            @error('numTel')
                            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                            @enderror
                        </div>

                        <!-- Champ Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" placeholder="{{ __('Enter your Email address') }}" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                            @enderror
                        </div>

                        <!-- Champ Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" placeholder="{{ __('Enter your password') }}" required>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                            @enderror
                        </div>

                        <!-- Champ Confirmation du mot de passe -->
                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">{{ __('Confirm password') }}</label>
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" placeholder="{{ __('Confirm your password') }}" required>
                        </div>

                        <!-- Bouton de création de compte -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark mt-3">
                                {{ __('Create account') }}
                            </button>
                        </div>

                        <!-- Lien vers la page de connexion -->
                        <div class="text-center mt-3">
                            <p class="mb-1">
                                {{ __('Already have an account?') }} <a href="{{ route('login') }}">{{ __('To Log In') }}</a>
                            </p>
                        </div>
                    </form>
            </form>
    </div>
@endsection
