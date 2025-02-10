@extends('layouts.app')

@section('title', 'Créer votre entreprise')

@section('content')
<div class="container d-flex flex-column" style="min-height: 100vh; margin-bottom: 2rem;">
    <div class="d-flex justify-content-between align-items-center w-100" style="position: relative;">
        {{-- Points de progression --}}
        <div class="d-flex justify-content-center align-items-center w-100 mt-5 mb-5">
            <span class="mx-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
            <span class="mx-2 bg-secondary rounded-circle" style="width: 12px; height: 12px;"></span>
        </div>
    </div>

    {{-- Titre --}}
    <h3 class="text-center mb-3">Créer le profil de votre entreprise</h3>

    <p class="text-center mb-3">Attention, notre plateforme n'est pas destinée aux professionnels de santé !</p>

    <form method="POST" action="{{ route('entreprise.typeRdv') }}">
        @csrf

        <div class="row mt-2">
            {{-- Champ Nom Entreprise --}}
            <div class="col-lg-6 mb-3">
                <label for="nomEntreprise" class="form-label">Nom de l'entreprise</label>
                <input id="nomEntreprise" type="text" class="form-control @error('nomEntreprise') is-invalid @enderror" 
                        name="nomEntreprise" value="{{ old('nomEntreprise', session('company.nomEntreprise', '')) }}" placeholder="Entrez le nom de votre entreprise" required>
                @error('nom')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Champ SIREN --}}
            <div class="col-lg-6 mb-3">
                <label for="siren" class="form-label">Numéro de SIREN</label>
                <input id="siren" type="text" class="form-control @error('siren') is-invalid @enderror" 
                        name="siren" value="{{ old('siren', session('company.siren', '')) }}" placeholder="Entrez le numéro de SIREN" required>
                @error('siren')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Champ Métier --}}
        <div class="mb-3">
            <label for="metier" class="form-label">Métier</label>
            <select id="metier" name="metier"
                    class="form-control @error('metier') is-invalid @enderror"
                    required>
                <option value="">Sélectionnez votre métier</option>
                <option value="Restaurant" {{ old('metier', session('company.metier')) == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                <option value="Coiffeur" {{ old('metier', session('company.metier')) == 'Coiffeur' ? 'selected' : '' }}>Coiffeur</option>
                <option value="Avocat" {{ old('metier', session('company.metier')) == 'Avocat' ? 'selected' : '' }}>Avocat</option>
                {{-- Ajoute ici la liste des métiers pertinents --}}
            </select>
            @error('metier')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>


        {{-- Champ Numéro de Téléphone --}}
        <div class="mb-3">
            <label for="numTel" class="form-label">Numéro de téléphone</label>
            <input id="numTel" type="text" class="form-control @error('numTel') is-invalid @enderror" 
                    name="numTel" value="{{ old('numTel', session('company.numTel', '')) }}" placeholder="Numéro de téléphone (** ** ** ** **)">
            @error('numTel')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Champ Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Adresse Email</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                    name="email" value="{{ old('email', session('company.email', '')) }}" placeholder="Entrez votre adresse Email" required>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Champ Adresse --}}
        <div class="mb-3">
            <label for="rue" class="form-label">Adresse</label>
            <input id="rue" type="text" class="form-control @error('rue') is-invalid @enderror" 
                    name="rue" value="{{ old('rue', session('company.rue', '')) }}" placeholder="Rue" required>
            @error('rue')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="codePostal" class="form-label">Code Postal</label>
                <input id="codePostal" type="text" class="form-control @error('codePostal') is-invalid @enderror" 
                        name="codePostal" value="{{ old('codePostal', session('company.codePostal', '')) }}" placeholder="Code Postal" required>
                @error('codePostal')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="ville" class="form-label">Ville</label>
                <input id="ville" type="text" class="form-control @error('ville') is-invalid @enderror" 
                        name="ville" value="{{ old('ville', session('company.ville', '')) }}" placeholder="Ville" required>
                @error('ville')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Champ Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description"
                      class="form-control @error('description') is-invalid @enderror"
                      name="description"
                      rows="3"
                      placeholder="Présentez brièvement votre entreprise">{{ old('description', session('company.description', '')) }}</textarea>
            @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>


        {{-- Bouton Créer le profil entreprise --}}
        <div class="d-grid mt-3">
            <button type="submit" class="btn btn-dark">
                Créer le profil entreprise
            </button>

            <a href="{{ route('clear.session') }}" class="btn btn-link mt-2">
                Retour à l'accueil
            </a>
        </div>
    </form>
</div>
@endsection