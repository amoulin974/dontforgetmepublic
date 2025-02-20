@extends('layouts.app')

@section('title', __('Verifying your email address'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Carte principale -->
                <div class="card">
                    <!-- En-tête de la carte -->
                    <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                    <div class="card-body">
                        <!-- Message de confirmation en cas de renvoi du lien -->
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <!-- Instructions pour l'utilisateur -->
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }},

                        <!-- Formulaire de renvoi du lien de vérification -->
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf <!-- Jeton CSRF pour la sécurité -->
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                                {{ __('Click here to request another') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
