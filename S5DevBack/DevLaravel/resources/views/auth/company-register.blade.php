@extends('layouts.app')

@section('content')
<!-- <div class="container d-flex flex-column align-items-center" style="min-height: 100vh;"> -->
<div class="container d-flex flex-column" style="min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center w-100" style="position: relative;">
        <!-- Bouton retour -->
        <a href="{{ route('register.company.register.user') }}">
            <i class="fa fa-arrow-left fa-lg" style="color: #000000;"></i>
        </a>

        <!-- Points de progression -->
        <div class="d-flex justify-content-center align-items-center w-100 mt-4 mb-4">
            <span class="mx-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
            <span class="mx-2 bg-primary rounded-circle" style="width: 12px; height: 12px;"></span>
            <span class="mx-2 bg-secondary rounded-circle" style="width: 12px; height: 12px;"></span>
        </div>
    </div>

        
    
        
        <!-- Titre -->
        <h3 class="text-center mb-3">Créer le profil de votre entreprise</h3>

        <p class="text-center mb-3">Attention, notre plateforme n'est pas destinée aux professionnels de santé !</p>

        <form method="POST" action="{{ route('register.company.register.typeRdv') }}">
            @csrf

            <!-- Champ Nom Entreprise-->
            <div class="mb-3 mt-2">
                <label for="nomEntreprise" class="form-label">Nom de l'entreprise</label>
                <input id="nomEntreprise" type="text" class="form-control @error('nomEntreprise') is-invalid @enderror" 
                       name="nomEntreprise" value="{{ old('nomEntreprise') }}" placeholder="Entrez le nom de votre entreprise" required>
                @error('nom')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Champ SIREN -->
            <div class="mb-3">
                <label for="siren" class="form-label">Numéro de SIREN</label>
                <input id="siren" type="text" class="form-control @error('siren') is-invalid @enderror" 
                       name="siren" value="{{ old('siren') }}" placeholder="Entrez le numéro de SIREN" required>
                @error('siren')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Champ Numéro de Téléphone -->
            <div class="mb-3">
                <label for="numTel" class="form-label">Numéro de téléphone</label>
                <input id="numTel" type="text" class="form-control @error('numTel') is-invalid @enderror" 
                       name="numTel" value="{{ old('numTel') }}" placeholder="Numéro de téléphone (** ** ** ** **)">
                @error('numTel')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Champ Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Adresse Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" placeholder="Entrez votre adresse Email" required>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Champ Adresse -->
            <div class="mb-3">
                <label for="rue" class="form-label">Adresse</label>
                <input id="rue" type="text" class="form-control @error('rue') is-invalid @enderror" 
                       name="rue" value="{{ old('rue') }}" placeholder="Rue" required>
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
                           name="codePostal" value="{{ old('codePostal') }}" placeholder="Code Postal" required>
                    @error('codePostal')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ville" class="form-label">Ville</label>
                    <input id="ville" type="text" class="form-control @error('ville') is-invalid @enderror" 
                           name="ville" value="{{ old('ville') }}" placeholder="Ville" required>
                    @error('ville')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Bouton Créer un compte -->
            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-dark">
                    Créer le profil entreprise
                </button>
            </div>
        </form>
    
</div>
@endsection