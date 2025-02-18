@extends('layouts.app')

@section('title', 'Rentrer votre email de réinitialisation')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-6">
        <a href="/" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h3 class="text-center mb-4">{{ __('Reset Password') }}</h3>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Adresse Email -->
            <div class="mb-4">
                <label for="email" class="form-label">
                    {{ __('Email Address') }}
                </label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                       placeholder="Adresse Email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Bouton Envoyer le lien -->
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-dark">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
