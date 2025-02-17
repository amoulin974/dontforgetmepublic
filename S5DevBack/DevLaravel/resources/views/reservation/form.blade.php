<form method="@yield('type_request')" action="" enctype="multipart/form-data" class="res-form">
    @csrf
    
    @if ($reservation->id)
        <h1>Réservation n°{{ $reservation->id }}</h1>
        {{-- @method('PUT') --}}
    @else
        <h1>Nouvelle réservation</h1>
    @endif

    <div class="form-group">
        <label for="dateRdv">Date de rendez-vous :</label>
        <input type="text" id="dateRdv" name="dateRdv" value="{{ old('dateRdv', $reservation->dateRdv) }}">
        @error('dateRdv')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="heureDeb">Heure de début :</label>
        <input type="text" id="heureDeb" name="heureDeb"  value="{{ old('heureDeb', $reservation->heureDeb) }}">
        @error('heureDeb')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="heureFin">Heure de fin :</label>
        <input type="text" id="heureFin" name="heureFin" value="{{ old('heureFin', $reservation->heureFin) }}">
        @error('heureFin')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="nbPersonnes">{{__("Number of people")}} :</label>
        <input type="text" id="nbPersonnes" name="nbPersonnes"  value="{{ old('nbPersonnes', $reservation->nbPersonnes) }}">
        @error('nbPersonnes')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-button">
        <button type="submit" class="primary-button">
            @if ($reservation -> id)
                Modifier la réservation
            @else
                Créer la réservation
            @endif
        </button>
    </div>
</form>
