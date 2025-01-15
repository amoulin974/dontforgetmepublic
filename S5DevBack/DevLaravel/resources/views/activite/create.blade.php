@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Nouveau service</h2>

    <form action="{{ route('entreprise.services.store', ['entreprise' => $entreprise->id]) }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="nom">Libellé du service</label>
            <input type="text" name="libelle" id="libelle" class="form-control @error('nom') is-invalid @enderror" placeholder="Libellé du service" value="{{ old('libelle') }}" required>
            @error('nom')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="duree">Durée du service (en minutes)</label>
            <input type="number" name="duree" id="duree" class="form-control @error('duree') is-invalid @enderror" placeholder="Durée du service (en minutes)" value="{{ old('duree') }}" required>
            @error('duree')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- <div class="form-group mb-3">
            <label for="employes">Attribuer le service à :</label>
            <div class="form-check">
                <input type="checkbox" name="attribuer_tous" id="attribuer_tous" class="form-check-input" value="1" onclick="toggleEmployes(this)">
                <label class="form-check-label" for="attribuer_tous">Tous</label>
            </div>
            <select name="employes[]" id="employes" class="form-control @error('employes') is-invalid @enderror" multiple>
                @foreach($employes as $employe)
                    <option value="{{ $employe->id }}">{{ $employe->nom }} {{ $employe->prenom }}</option>
                @endforeach 
            </select>
            @error('employes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror 
        </div> --}}

        <div class="d-flex justify-content-between">
            <a href="{{ route('entreprise.services.index', ['entreprise' => $entreprise->id]) }}" class="btn btn-light">Annuler</a>
            <button type="submit" class="btn btn-dark">Valider</button>
        </div>
    </form>
</div>

<script>
    function toggleEmployes(checkbox) {
        const employesSelect = document.getElementById('employes');
        employesSelect.disabled = checkbox.checked;
        if (checkbox.checked) {
            employesSelect.value = ''; // Réinitialise la sélection
        }
    }
</script>
@endsection
