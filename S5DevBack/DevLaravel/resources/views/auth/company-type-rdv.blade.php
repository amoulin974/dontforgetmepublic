@extends('layouts.app')

@section('content')

<div class="container">
    <div id="dynamic-form" class="mt-5">
        <!-- Barre de progression -->
        <div class="progress mb-4">
            <div id="progress-bar" class="progress-bar bg-primary" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <button class="btn btn-secondary w-100 mt-4" id="retour">Retour</button>

        <!-- Étape 1 : Nombre de clients par créneau -->
        <div class="step" id="step1">
            <h2 class="mb-4">Traitez-vous un ou plusieurs clients lors d'un créneau ?</h2>
            <div class="row">
                <div class="col-6">
                    <button class="btn btn-outline-primary btn-nav w-100 py-4" answer=0>
                        Un seul client
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-primary btn-nav w-100 py-4" answer=1>
                        Plusieurs clients
                    </button>
                </div>
            </div>
        </div>


        <!-- Étape 2 : Qui sélectionne le créneau ? -->
        <div class="step d-none" id="step2">
            <h2 class="mb-4">Qui sélectionne le créneau ?</h2>
            <div class="row">
                <div class="col-6">
                    <button class="btn btn-outline-primary btn-nav w-100 py-4" answer=0>
                        Seulement vous
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-primary btn-nav w-100 py-4" answer=1>
                        Le client et vous
                    </button>
                </div>
            </div>
        </div>

        <!-- Étape 3 : Affectez-vous un salarié pour chaque client ? -->
        <div class="step d-none" id="step3">
            <h2 class="mb-4">Affectez-vous un salarié pour chaque client ?</h2>
            <div class="row">
                <div class="col-4">
                    <button class="btn btn-outline-primary btn-nav w-100 py-4" answer=0>
                        Aucun
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-primary btn-nav w-100 py-4" answer=1>
                        Un seul
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-primary btn-nav w-100 py-4" answer=2>
                        Plusieurs
                    </button>
                </div>
            </div>
        </div>

        <!-- Étape 4 : Placez-vous vos clients dans votre enseigne ? -->
        <div class="step d-none" id="step4">
            <h2 class="mb-4">Placez-vous vos clients dans votre enseigne ?</h2>
            <div class="row">
                <div class="col-6">
                    <a class="btn btn-outline-primary btn-submit w-100 py-4" answer=0 href="{{ route('register.company.register.recap') }}">
                        Oui
                    </a>
                    <!-- <button class="btn btn-outline-primary btn-nav w-100 py-4" data-next="step5-recap" href="{{ route('register.company.register.recap') }}">
                        Oui
                    </button> -->
                </div>
                <div class="col-6">
                    <a class="btn btn-outline-primary btn-submit w-100 py-4" answer=1 href="{{ route('register.company.register.recap') }}">
                        Non
                    </a>
                    <!-- <button class="btn btn-outline-primary btn-nav w-100 py-4" data-next="step5-recap" href="{{ route('register.company.register.recap') }}">
                        Non
                    </button> -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/register.js') }}"></script>
@endsection