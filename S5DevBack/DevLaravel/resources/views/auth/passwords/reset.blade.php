@extends('layouts.app')

@section('title', 'Réinitialiser votre mot de passe')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-6">
        <a href="/" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h3 class="text-center mb-4">Réinitialiser le mot de passe</h3>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Adresse Email -->
            <div class="mb-4">
                <label for="email" class="form-label">Adresse Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus 
                       placeholder="Adresse Email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Nouveau mot de passe -->
            <div class="mb-4">
                <label for="password" class="form-label">Nouveau mot de passe</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="new-password" placeholder="Entrez un nouveau mot de passe">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Confirmer le mot de passe -->
            <div class="mb-4">
                <label for="password-confirm" class="form-label">Confirmer le mot de passe</label>
                <input id="password-confirm" type="password" class="form-control" 
                       name="password_confirmation" required autocomplete="new-password" placeholder="Confirmez le mot de passe">
            </div>

            <!-- Bouton de réinitialisation -->
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-dark">
                    Réinitialiser le mot de passe
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
