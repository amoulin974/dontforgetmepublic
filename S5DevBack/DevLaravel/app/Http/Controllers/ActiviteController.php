<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activite;
use App\Models\Entreprise;

class ActiviteController extends Controller
{
    public function index(Entreprise $entreprise)
    {
        //$entrepriseActuelle = session('entreprise');
        /*if (!$entrepriseActuelle) {
            return redirect()->route('dashboard')->with('error', 'Aucune entreprise active sélectionnée.');
        }*/

        //$services = Activite::where('entreprise_id', $entrepriseActuelle->id)->get();
        $services = Activite::where('idEntreprise', $entreprise->id)->get();
        return view('activite.index', ['entreprise' => $entreprise], compact('services'));
    }

    public function create(Entreprise $entreprise)
    {
        return view('activite.create', ['entreprise' => $entreprise]);
    }

    /* public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'duree' => 'nullable|integer|min:1',
        ]);

        Activite::create($request->all());

        return redirect()->route('activite.index')->with('success', 'Service créé avec succès.');
    } */
    public function store(Request $request, Entreprise $entreprise)
    {
        // \Log::info('Méthode HTTP utilisée : ' . $request->method());

        $request->validate([
            'libelle' => 'required|string|max:255',
            'duree' => 'required|integer|min:1', // Durée en minutes
        ]);
    
        // Conversion de la durée en format H:i:s
        $dureeInTimeFormat = gmdate('H:i:s', $request->duree * 60);
    
        Activite::create([
            'libelle' => $request->libelle,
            'duree' => $dureeInTimeFormat,
            'idEntreprise' => $entreprise->id
        ]);

        
    
        return redirect()->route('entreprise.services.index', ['entreprise' => $entreprise->id])->with('success', 'Service créé avec succès.');
    } 
    
    

    /*
    public function show($id)
    {
        $service = Activite::findOrFail($id);
        return view('services.show', compact('service'));
    }*/

    public function edit(Entreprise $entreprise, $id)
    {
        //$entreprise = Entreprise::findOrFail($entreprise);
        /* $service = Activite::findOrFail($id);
        return view('activite.edit', ['entreprise' => $entreprise], compact('service')); */
        $service = Activite::where('id', $id)->where('idEntreprise', $entreprise->id)->firstOrFail();
        return view('activite.edit', ['entreprise' => $entreprise, 'service' => $service]);
    }

    /*public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'duree' => 'required|integer|min:1',
        ]);

        $service = Activite::findOrFail($id);
        $service->update($request->all());

        return redirect()->route('services.index')->with('success', 'Service mis à jour avec succès.');
    }*/

    public function update(Request $request, $id, Entreprise $entreprise)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'duree' => 'required|integer|min:1', // Durée en minutes
        ]);

        $service = Activite::findOrFail($id);

        // Convertir la durée (minutes) en format H:i:s
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

        return redirect()->route('entreprise.services.index', ['entreprise' => $entreprise->id])->with('success', 'Service supprimé avec succès.');
    }
}
