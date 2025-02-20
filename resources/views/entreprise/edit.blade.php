@extends('base')

@section('title_base', "{{__('Edit2')}}" . $entreprise->libelle)

@section('content')
    <div class="container">
        <h1>{{__('Edit business')}}</h1>
        <form action="{{ route('entreprise.update', ['entreprise' => $entreprise->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Section 1 : Images --}}
            <div class="mb-4">
                <h3>Images</h3>
                {{-- Si l'entreprise a une image, elle est affichée --}}
                @if($entreprise->cheminImg)
                    <img src="{{ json_decode($entreprise->cheminImg)[0] }}" style="display: block; margin:auto;" alt="{{ $entreprise->libelle }}" height="100vh" width="100vh">
                    {{-- Sinon, une image par défaut est affichée --}}
                @else
                    <img src="https://www.map24.com/wp-content/uploads/2021/11/6784174_s.jpg" style="display: block; margin:auto;" alt="{{ $entreprise->libelle }}" height="100vh" width="100vh">
                @endif
                <div>
                    <label for="new_images">{{__('Add images')}}</label>
                    <input type="file" name="new_images[]" multiple class="form-control">
                </div>
            </div>

            {{-- Section 2 : Informations générales --}}
            <div class="mb-4">
<<<<<<< HEAD:S5DevBack/DevLaravel/resources/views/entreprise/edit.blade.php
                <h3>{{__('General information')}}</h3>
=======
                <h3>Informations générales</h3>
                {{-- Champ pour le nom de l'entreprise --}}
>>>>>>> dfm-refactor:resources/views/entreprise/edit.blade.php
                <div class="mb-2">
                    <label for="libelle">{{__('Business name')}}</label>
                    <input type="text" name="libelle" id="libelle" value="{{ $entreprise->libelle }}" class="form-control" required>
                </div>
                {{-- Champ pour le numéro SIREN --}}
                <div class="mb-2">
                    <label for="siren">{{__('SIREN')}}</label>
                    <input type="text" name="siren" id="siren" value="{{ $entreprise->siren }}" class="form-control" required>
                </div>
                <div class="mb-2">
<<<<<<< HEAD:S5DevBack/DevLaravel/resources/views/entreprise/edit.blade.php
                    <label for="rue">{{__('Avenue')}}</label>
                    <input type="text" name="rue" id="rue" 
                           value="{{ explode(',', $entreprise->adresse)[0] ?? '' }}" 
=======
                    <label for="metier" class="form-label">Métier</label>
                    <select name="metier" id="metier" class="form-select @error('metier') is-invalid @enderror" required>
                        <option value="">Sélectionnez votre métier</option>
                        <option value="Restaurant" {{ old('metier', $entreprise->metier) == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                        <option value="Coiffeur" {{ old('metier', $entreprise->metier) == 'Coiffeur' ? 'selected' : '' }}>Coiffeur</option>
                        <option value="Avocat" {{ old('metier', $entreprise->metier)  == 'Avocat' ? 'selected' : '' }}>Avocat</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="rue">Rue</label>
                    <input type="text" name="rue" id="rue"
                           value="{{ explode(',', $entreprise->adresse)[0] ?? '' }}"
>>>>>>> dfm-refactor:resources/views/entreprise/edit.blade.php
                           class="form-control" required>
                </div>
                {{-- Champ pour le code postal --}}
                <div class="mb-2">
<<<<<<< HEAD:S5DevBack/DevLaravel/resources/views/entreprise/edit.blade.php
                    <label for="codePostal">{{__('Postal code')}}</label>
                    <input type="text" name="codePostal" id="codePostal" 
                           value="{{ explode(' ', trim(explode(',', $entreprise->adresse)[1] ?? ''))[0] ?? '' }}" 
=======
                    <label for="codePostal">Code postal</label>
                    <input type="text" name="codePostal" id="codePostal"
                           value="{{ explode(' ', trim(explode(',', $entreprise->adresse)[1] ?? ''))[0] ?? '' }}"
>>>>>>> dfm-refactor:resources/views/entreprise/edit.blade.php
                           class="form-control" required>
                </div>
                {{-- Champ pour la ville --}}
                <div class="mb-2">
<<<<<<< HEAD:S5DevBack/DevLaravel/resources/views/entreprise/edit.blade.php
                    <label for="ville">{{__('City')}}</label>
                    <input type="text" name="ville" id="ville" 
                           value="{{ implode(' ', array_slice(explode(' ', trim(explode(',', $entreprise->adresse)[1] ?? '')), 1)) }}" 
=======
                    <label for="ville">Ville</label>
                    <input type="text" name="ville" id="ville"
                           value="{{ implode(' ', array_slice(explode(' ', trim(explode(',', $entreprise->adresse)[1] ?? '')), 1)) }}"
>>>>>>> dfm-refactor:resources/views/entreprise/edit.blade.php
                           class="form-control" required>
                </div>
                {{-- Champ pour la description de l'entreprise --}}
                <div class="mb-2">
                    <label for="description">{{__('Description')}}</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ $entreprise->description }}</textarea>
                </div>
                {{-- Champ pour l'adresse email --}}
                <div class="mb-2">
                    <label for="email">{{__('Email Address')}}</label>
                    <input type="email" name="email" id="email" value="{{ $entreprise->email }}" class="form-control" required>
                </div>
                {{-- Champ pour le numéro de téléphone --}}
                <div class="mb-2">
                    <label for="numTel">{{__('Phone number')}}</label>
                    <input type="text" name="numTel" id="numTel" value="{{ $entreprise->numTel }}" class="form-control" required>
                </div>
            </div>

            {{-- Section 3 : Paramètres des rendez-vous --}}
            <div class="mb-4">
<<<<<<< HEAD:S5DevBack/DevLaravel/resources/views/entreprise/edit.blade.php
                <h3>{{__('Booking settings')}}</h3>
                {{-- Question 1 --}}
=======
                <h3>Paramètres des rendez-vous</h3>
                {{-- Question 1 : Traitez-vous un ou plusieurs clients par créneau ? --}}
>>>>>>> dfm-refactor:resources/views/entreprise/edit.blade.php
                <div class="row align-items-center mb-3">
                    <div class="col-md-8">
                        <label for="question_0" class="form-label">{{__('Do you treat one or several clients per schedule?')}}</label>
                    </div>
                    <div class="col-md-4">
                        <select name="question_0" id="question_0" class="form-select">
                            <option value="0" {{ json_decode($entreprise->typeRdv)[0] == 0 ? 'selected' : '' }}>{{__('One')}}</option>
                            <option value="1" {{ json_decode($entreprise->typeRdv)[0] == 1 ? 'selected' : '' }}>{{__('Several')}}</option>
                        </select>
                    </div>
                </div>

                {{-- Champ capacité max, affiché seulement si "Plusieurs" est sélectionné --}}
                <div class="row align-items-center mb-3" id="capaciteMaxContainer" style="display: none;">
                    <div class="col-md-8">
                        <label for="capaciteMax" class="form-label">Capacité maximale de clients par créneau :</label>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="capaciteMax" id="capaciteMax" class="form-control @error('capaciteMax') is-invalid @enderror"
                               value="{{ old('capaciteMax', $entreprise->capaciteMax ?? 1) }}" 
                               min="1">
                    </div>
                </div>    

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        let question0 = document.getElementById("question_0");
                        let capaciteMaxContainer = document.getElementById("capaciteMaxContainer");

                        function toggleCapaciteMax() {
                            capaciteMaxContainer.style.display = question0.value == "1" ? "flex" : "none";
                        }

                        // Vérification initiale
                        toggleCapaciteMax();

                        // Ajout de l'écouteur d'événement pour les changements
                        question0.addEventListener("change", toggleCapaciteMax);
                    });
                </script>

                
                {{-- Question 2 --}}
                <div class="row align-items-center mb-3">
                    <div class="col-md-8">
                        <label for="question_1" class="form-label">{{__('Who chooses the schedule?')}}</label>
                    </div>
                    <div class="col-md-4">
                        <select name="question_1" id="question_1" class="form-select">
                            <option value="0" {{ json_decode($entreprise->typeRdv)[1] == 0 ? 'selected' : '' }}>{{__('You only')}}</option>
                            <option value="1" {{ json_decode($entreprise->typeRdv)[1] == 1 ? 'selected' : '' }}>{{__('You and the client')}}</option>
                        </select>
                    </div>
                </div>

                {{-- Question 3 : Affectez-vous un salarié pour chaque client ? --}}
                <div class="row align-items-center mb-3">
                    <div class="col-md-8">
                        <label for="question_2" class="form-label">{{__('Do you assign an employee to each client?')}}</label>
                    </div>
                    <div class="col-md-4">
                        <select name="question_2" id="question_2" class="form-select">
                            <option value="0" {{ json_decode($entreprise->typeRdv)[2] == 0 ? 'selected' : '' }}>{{__('None')}}</option>
                            <option value="1" {{ json_decode($entreprise->typeRdv)[2] == 1 ? 'selected' : '' }}>{{__('One')}}</option>
                            <option value="2" {{ json_decode($entreprise->typeRdv)[2] == 2 ? 'selected' : '' }}>{{__('Several')}}</option>
                        </select>
                    </div>
                </div>

                {{-- Question 4 : Placez-vous vos clients dans votre enseigne ? --}}
                <div class="row align-items-center mb-3">
                    <div class="col-md-8">
                        <label for="question_3" class="form-label">{{__('Do you place your clients in your brand?')}}</label>
                    </div>
                    <div class="col-md-4">
                        <select name="question_3" id="question_3" class="form-select">
                            <option value="0" {{ json_decode($entreprise->typeRdv)[3] == 0 ? 'selected' : '' }}>{{__('Yes')}}</option>
                            <option value="1" {{ json_decode($entreprise->typeRdv)[3] == 1 ? 'selected' : '' }}>{{__('No')}}</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section 4 : Squelette des notifications --}}
            <div class="mb-4">
<<<<<<< HEAD:S5DevBack/DevLaravel/resources/views/entreprise/edit.blade.php
                <h3>{{__('Notifications')}}</h3>
                <p>{{__('That part will be managed later.')}}</p>
=======
                <h3>Notifications</h3>
                {{-- Notification qui sera ajoutée plus tard --}}
                <p>Cette partie sera gérée ultérieurement.</p>
>>>>>>> dfm-refactor:resources/views/entreprise/edit.blade.php
            </div>

            {{-- Bouton d'envoi --}}
            <button type="submit" class="btn btn-success">{{__('Save changes')}}</button>
        </form>
    </div>
@endsection
