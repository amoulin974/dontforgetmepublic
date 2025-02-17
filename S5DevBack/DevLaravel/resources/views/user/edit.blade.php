@extends('base')

@section('title_base', Auth::user()->nom . ' ' . Auth::user()->prenom)
@section('profile_active', 'active')

@section('content')
<div class="container">
    <h1>{{__('Edit profile')}}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">{{__('Last name')}}</label>
            <input type="text" id="nom" name="nom" class="form-control" value="{{ old('nom', $user->nom) }}" required>
            @error('nom') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="prenom" class="form-label">{{__('First name')}}</label>
            <input type="text" id="prenom" name="prenom" class="form-control" value="{{ old('prenom', $user->prenom) }}" required>
            @error('prenom') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{__('Email Address')}}</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        {{-- <div class="mb-3">
            <label for="password" class="form-label">Nouveau mot de passe (optionnel)</label>
            <input type="password" id="password" name="password" class="form-control">
            @error('password') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div> --}}

        <div class="mb-3">
            <label for="numTel" class="form-label">{{__('Phone number')}}</label>
            <input type="text" id="numTel" name="numTel" class="form-control" value="{{ old('numTel', $user->numTel) }}">
            @error('numTel') <div class="text-danger">{{ $message }}</div> @enderror
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
        <a href="{{ route('profile.index') }}" class="btn btn-secondary">{{__('Cancel')}}</a>
    </form>
</div>
@endsection
