<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activite;

class ActiviteController extends Controller
{
    public function index()
    {
        //$entrepriseActuelle = session('entreprise');
        $entrepriseIdTest = 1;
        /*if (!$entrepriseActuelle) {
            return redirect()->route('dashboard')->with('error', 'Aucune entreprise active sélectionnée.');
        }*/

        //$services = Activite::where('entreprise_id', $entrepriseActuelle->id)->get();
        $services = Activite::where('idEntreprise', $entrepriseIdTest)->get();
        return view('activite.index', compact('services'));
    }

    public function create()
    {
        return view('activite.create');
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
    public function store(Request $request)
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
            'idEntreprise' => 1
        ]);
    
        return redirect()->route('services.index')->with('success', 'Service créé avec succès.');
    } 
    
    

    /*
    public function show($id)
    {
        $service = Activite::findOrFail($id);
        return view('services.show', compact('service'));
    }*/

    public function edit($id)
    {
        $service = Activite::findOrFail($id);
        return view('activite.edit', compact('service'));
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

    public function update(Request $request, $id)
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

        return redirect()->route('services.index')->with('success', 'Service mis à jour avec succès.');
    }


    public function destroy($id)
    {
        $service = Activite::findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service supprimé avec succès.');
    }
}
