<form method="@yield('type_request')" action="" enctype="multipart/form-data" class="res-form">
    @csrf
    
    @if ($reservation->id)
        <h1>{{__('Booking #')}}{{ $reservation->id }}</h1>
        {{-- @method('PUT') --}}
    @else
        <h1>{{__('New booking')}}</h1>
    @endif

    <div class="form-group">
        <label for="dateRdv">{{__('Appointment date')}} :</label>
        <input type="text" id="dateRdv" name="dateRdv" value="{{ old('dateRdv', $reservation->dateRdv) }}">
        @error('dateRdv')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="heureDeb">{{__("Start time")}} :</label>
        <input type="text" id="heureDeb" name="heureDeb"  value="{{ old('heureDeb', $reservation->heureDeb) }}">
        @error('heureDeb')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="heureFin">{{__("End time")}} :</label>
        <input type="text" id="heureFin" name="heureFin" value="{{ old('heureFin', $reservation->heureFin) }}">
        @error('heureFin')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-group">
        <label for="nbPersonnes">{{__("Amount of people")}} :</label>
        <input type="text" id="nbPersonnes" name="nbPersonnes"  value="{{ old('nbPersonnes', $reservation->nbPersonnes) }}">
        @error('nbPersonnes')
            <span class="error-message">{{ $message }}</span>
        @enderror
    </div>
    <div class="form-button">
        <button type="submit" class="primary-button">
            @if ($reservation -> id)
                {{__("Edit booking")}}
            @else
                Créer la réservation
            @endif
        </button>
    </div>
</form>
