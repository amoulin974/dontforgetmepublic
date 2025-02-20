@extends('base')

@section('title_base', Auth::user()->nom . ' ' . Auth::user()->prenom)
@section('profile_active', 'active')

@section('content')
<div class="container">
    <h1>Modifier mon profil</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="last_name" class="form-label">Nom</label>
            <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name', $user->nom) }}" required>
            @error('last_name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="first_name" class="form-label">Prénom</label>
            <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', $user->prenom) }}" required>
            @error('first_name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Numéro de téléphone</label>
            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->numTel) }}">
            @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3 d-flex align-items-center">
            <label for="typeNotif" class="form-label me-2 mb-0">Type de notification par défaut : </label>
            <select name="typeNotif" id="typeNotif" class="form-select w-auto">
                <option value="" {{ empty(old('typeNotif', $user->typeNotif)) ? 'selected' : '' }}>Sélectionner</option>
                <option value="SMS" {{ old('typeNotif', $user->typeNotif) == 'SMS' ? 'selected' : '' }}>SMS</option>
                <option value="Email" {{ old('typeNotif', $user->typeNotif) == 'Email' ? 'selected' : '' }}>Email</option>
            </select>
        </div>

        <div class="mb-3 d-flex align-items-center">
            <label for="delaiAvantNotif" class="form-label me-2 mb-0">Délai d'envoi avant le rendez-vous :</label>
            <select name="delaiAvantNotif" id="delaiAvantNotif" class="form-select w-auto">
                <option value="" {{ empty(old('delaiAvantNotif', $user->delaiAvantNotif)) ? 'selected' : '' }}>Sélectionner</option>
                <option value="1 jour" {{ old('delaiAvantNotif', $user->delaiAvantNotif) == '1 jour' ? 'selected' : '' }}>1 jour</option>
                <option value="2 jours" {{ old('delaiAvantNotif', $user->delaiAvantNotif) == '2 jours' ? 'selected' : '' }}>2 jours</option>
                <option value="1 semaine" {{ old('delaiAvantNotif', $user->delaiAvantNotif) == '1 semaine' ? 'selected' : '' }}>1 semaine</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Mettre à jour</button>
        <a href="{{ route('profile.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
