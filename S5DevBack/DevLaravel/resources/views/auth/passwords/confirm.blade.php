@extends('layouts.app')

@section('title', 'Nouveau mdp')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <!-- En-tête de la carte pour confirmer le mot de passe -->
                    <div class="card-header">{{ __('Confirm Password') }}</div>

                    <div class="card-body">
                        {{ __('Please confirm your password before continuing.') }}

                        <!-- Formulaire de confirmation de mot de passe -->
                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <div class="row mb-3">
                                <!-- Champ de saisie pour le mot de passe -->
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    <!-- Message d'erreur en cas de validation incorrecte du mot de passe -->
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <!-- Bouton pour confirmer le mot de passe -->
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Confirm Password') }}
                                    </button>

                                    <!-- Lien pour réinitialiser le mot de passe si oublié -->
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
