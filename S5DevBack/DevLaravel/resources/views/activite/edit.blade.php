@extends('layouts.app')

@include('base')

@section('title', 'Modification du service ' . $service->libelle)

@section('content')
    <div class="container">
        <h2 class="mb-4">Modifier un service</h2>

        <!-- Formulaire de modification du service -->
        <form action="{{ route('entreprise.services.update', ['entreprise' => $entreprise->id, 'id' => $service->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Champ pour le libellé du service -->
            <div class="form-group mb-3">
                <label for="libelle">Libellé du service</label>
                <input type="text" name="libelle" id="libelle" class="form-control @error('libelle') is-invalid @enderror" value="{{ old('libelle', $service->libelle) }}" required>
                <!-- Message d'erreur pour le libellé du service -->
                @error('libelle')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Champ pour la durée du service -->
            <div class="form-group mb-3">
                <label for="duree">Durée du service (en minutes)</label>
                <input type="number" name="duree" id="duree" class="form-control @error('duree') is-invalid @enderror" value="{{ old('duree', $service->duree) }}" required>
                <!-- Message d'erreur pour la durée du service -->
                @error('duree')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Boutons d'annulation et de validation -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('entreprise.services.index', ['entreprise' => $entreprise->id]) }}" class="btn btn-light">Annuler</a>
                <button type="submit" class="btn btn-dark">Valider</button>
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
                employesSelect.value = ''; // Réinitialise la sélection
            }
        }
    </script>
@endsection
