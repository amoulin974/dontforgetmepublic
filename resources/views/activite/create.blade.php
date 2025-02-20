@extends('layouts.app')

@include('base')

@section('title', __('Create a service'))

@section('content')
    <div class="container">
        <h2 class="mb-4">{{ __('New service') }}</h2>

        <!-- Formulaire de création d'un nouveau service -->
        <form action="{{ route('entreprise.services.store', ['entreprise' => $entreprise->id]) }}" method="POST">
            @csrf

            <!-- Champ pour le libellé du service -->
            <div class="form-group mb-3">
                <label for="nom">{{ __('Service label') }}</label>
                <input type="text" name="libelle" id="libelle" class="form-control @error('nom') is-invalid @enderror" placeholder="{{ __('New service') }}" value="{{ old('libelle') }}" required>
                <!-- Message d'erreur pour le libellé du service -->
                @error('nom')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        <div class="form-group mb-3">
            <label for="duree">{{ __('Service duration') }}</label>
            <input type="time" name="duree" id="duree" class="form-control @error('duree') is-invalid @enderror" placeholder="{{ __('Service duration') }}" value="{{ old('duree') }}" required>
            @error('duree')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="nbrPlaces">{{ __('Number of places available') }}</label>
            <input type="number" name="nbrPlaces" id="nbrPlaces" class="form-control @error('nbrPlaces') is-invalid @enderror" placeholder="{{ __('Number of places available') }}" value="{{ old('nbrPlaces') }}" required>
            @error('nbrPlaces')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

            <!-- Boutons d'annulation et de validation -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('entreprise.services.index', ['entreprise' => $entreprise->id]) }}" class="btn btn-light">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-dark">{{ __('Validate') }}</button>
            </div>
        </form>
    </div>

    <!-- Script JavaScript pour activer/désactiver la sélection des employés -->
    <script>
        /**
         * Fonction pour activer ou désactiver la sélection des employés
         * @param {HTMLInputElement} checkbox - La case à cocher pour activer/désactiver.
         */
        function toggleEmployes(checkbox) {
            const employesSelect = document.getElementById('employes');
            employesSelect.disabled = checkbox.checked;
            if (checkbox.checked) {
                employesSelect.value = '';
            }
        }
    </script>
@endsection
