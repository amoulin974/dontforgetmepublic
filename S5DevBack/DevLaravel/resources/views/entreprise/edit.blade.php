@extends('base')

@section('title_base', "Modifier " . $entreprise->libelle)

@section('content')
    <div class="container">
        <h1>Modifier l'entreprise</h1>
        <form action="{{ route('entreprise.update', ['entreprise' => $entreprise->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Section 1 : Images --}}
            {{-- <div class="mb-4">
                <h3>Images</h3>
                @foreach ($entreprise->images as $image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $image->path) }}" alt="Image de l'entreprise" width="150">
                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"> Supprimer
                    </div>
                @endforeach
                <div>
                    <label for="new_images">Ajouter des images</label>
                    <input type="file" name="new_images[]" multiple class="form-control">
                </div>
            </div> --}}

            {{-- Section 2 : Informations générales --}}
            <div class="mb-4">
                <h3>Informations générales</h3>
                <div class="mb-2">
                    <label for="name">Nom de l'entreprise</label>
                    <input type="text" name="name" id="name" value="{{ $entreprise->name }}" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label for="siren">SIREN</label>
                    <input type="text" name="siren" id="siren" value="{{ $entreprise->siren }}" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label for="address">Adresse</label>
                    <input type="text" name="address" id="address" value="{{ $entreprise->address }}" class="form-control" placeholder="Rue" required>
                </div>
                <div class="mb-2">
                    <label for="postal_code">Code postal</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ $entreprise->postal_code }}" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label for="city">Ville</label>
                    <input type="text" name="city" id="city" value="{{ $entreprise->city }}" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ $entreprise->description }}</textarea>
                </div>
                <div class="mb-2">
                    <label for="email">Adresse email</label>
                    <input type="email" name="email" id="email" value="{{ $entreprise->email }}" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label for="phone">Numéro de téléphone</label>
                    <input type="text" name="phone" id="phone" value="{{ $entreprise->phone }}" class="form-control" required>
                </div>
            </div>

            {{-- Section 3 : Paramètres des rendez-vous --}}
            <div class="mb-4">
                <h3>Paramètres des rendez-vous</h3>
                {{-- @foreach ($questions as $question)
                    <div class="mb-2">
                        <label for="question_{{ $loop->index }}">{{ $question['label'] }}</label>
                        <select name="question_{{ $loop->index }}" id="question_{{ $loop->index }}" class="form-control">
                            @foreach ($question['options'] as $value => $label)
                                <option value="{{ $value }}" {{ $entreprise->getQuestionValue($loop->index) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach --}}
            </div>

            {{-- Section 4 : Squelette des notifications --}}
            <div class="mb-4">
                <h3>Notifications</h3>
                <p>Cette partie sera gérée ultérieurement.</p>
            </div>

            {{-- Bouton d'envoi --}}
            <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
        </form>
    </div>
@endsection
