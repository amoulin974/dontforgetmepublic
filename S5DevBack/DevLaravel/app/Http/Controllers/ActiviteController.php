<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activite;
use App\Models\Entreprise;

class ActiviteController extends Controller
{
    public function index(Entreprise $entreprise)
    {
        $services = Activite::where('idEntreprise', $entreprise->id)->get();
        return view('activite.index', ['entreprise' => $entreprise], compact('services'));
    }

    public function create(Entreprise $entreprise)
    {
        return view('activite.create', ['entreprise' => $entreprise]);
    }

    public function store(Request $request, Entreprise $entreprise)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'duree' => 'required|integer|min:1', 
        ]);

        $dureeInTimeFormat = gmdate('H:i:s', $request->duree * 60);
    
        $activite = Activite::create([
            'libelle' => $request->libelle,
            'duree' => $dureeInTimeFormat,
            'idEntreprise' => $entreprise->id
        ]);

        $activite->travailler_users()->attach(auth()->id(), [
            'idEntreprise' => $entreprise->id,
            'statut' => 'Admin',
        ]);

        if ($entreprise->activites()->count() === 1) {
            $entreprise->update(['publier' => 1]);
        }
    
        return redirect()->route('entreprise.services.index', ['entreprise' => $entreprise->id])->with('success', 'Service créé avec succès.');
    } 

    public function edit(Entreprise $entreprise, $id)
    {
        $service = Activite::where('id', $id)->where('idEntreprise', $entreprise->id)->firstOrFail();
        return view('activite.edit', ['entreprise' => $entreprise, 'service' => $service]);
    }

    public function update(Request $request, $id, Entreprise $entreprise)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'duree' => 'required|integer|min:1', 
        ]);

        $service = Activite::findOrFail($id);

        $dureeInTimeFormat = gmdate('H:i:s', $request->duree * 60);

        $service->update([
            'libelle' => $request->nom,
            'duree' => $dureeInTimeFormat,
        ]);

        return redirect()->route('entreprise.services.index', ['entreprise' => $entreprise->id])->with('success', 'Service mis à jour avec succès.');
    }

    public function destroy(Entreprise $entreprise, $id)
    {
        $service = Activite::findOrFail($id);
        $service->delete();
        $service->travailler_users()->detach();

        if ($entreprise->activites()->count() === 0) {
            $entreprise->update(['publier' => 0]); 
        }

        return redirect()->route('entreprise.services.index', ['entreprise' => $entreprise->id])->with('success', 'Service supprimé avec succès.');
    }
}
