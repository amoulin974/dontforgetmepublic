@extends('layouts.app')

@section('title', __('Login user'))

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-6">
        <a href="/" class="btn btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h3 class="text-center mb-4">{{__('Log In')}}</h3>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Adresse Email -->
            <div class="mb-4">
                <label for="email" class="form-label">{{__('Email Address')}}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                       placeholder="{{__('Email Address')}}">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div class="mb-4">
                <label for="password" class="form-label">{{__('Password')}}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="current-password" 
                       placeholder="{{__('Enter your password')}}">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Bouton "Je me connecte" -->
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-dark">
                    {{__('Log In2')}}
                </button>
            </div>

            <!-- Liens supplémentaires -->
            <div class="text-center">
                <p class="mb-1">
                    {{__("Don't have an account?")}} <a href="{{ route('register.choose.account.type') }}">{{__('Register2')}}</a>
                </p>
                <p>
                    {{__('Forgot Your Password?')}} <a href="{{ route('password.request') }}">{{__('Click here')}}</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
