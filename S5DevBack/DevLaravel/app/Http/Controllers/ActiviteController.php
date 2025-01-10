<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activite;
use App\Models\Entreprise;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ActiviteController extends Controller
{
    public function index(Entreprise $entreprise)
    {
        //$entrepriseActuelle = session('entreprise');
        /*if (!$entrepriseActuelle) {
            return redirect()->route('dashboard')->with('error', 'Aucune entreprise active sélectionnée.');
        }*/
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;

        if($isAdmin){
            //$services = Activite::where('entreprise_id', $entrepriseActuelle->id)->get();
            $services = Activite::where('idEntreprise', $entreprise->id)->get();
            return view('activite.index', ['entreprise' => $entreprise], compact('services'));
        }
        else {
            return redirect()->route('entreprise.index');
        }
    }

    public function create(Entreprise $entreprise)
    {
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;
        if(!$isAdmin){
            return redirect()->route('entreprise.index');
        }else {
            return view('activite.create', ['entreprise' => $entreprise]);
        }
    }

    public function store(Request $request, Entreprise $entreprise)
    {
        // \Log::info('Méthode HTTP utilisée : ' . $request->method());
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;

        if(!$isAdmin){
            return redirect()->route('entreprise.index');
        }
        else{
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
    } 

    public function edit(Entreprise $entreprise, $id)
    {
        //$entreprise = Entreprise::findOrFail($entreprise);
        /* $service = Activite::findOrFail($id);
        return view('activite.edit', ['entreprise' => $entreprise], compact('service')); */
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;

        if(!$isAdmin){
            return redirect()->route('entreprise.index');
        }
        else{
            $service = Activite::where('id', $id)->where('idEntreprise', $entreprise->id)->firstOrFail();
        return view('activite.edit', ['entreprise' => $entreprise, 'service' => $service]);
        }
    }

    public function update(Request $request, $id, Entreprise $entreprise)
    {
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;

        if(!$isAdmin){
            return redirect()->route('entreprise.index');
        }
        else{
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
    }

    public function destroy(Entreprise $entreprise, $id)
    {
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;
        if(!$isAdmin){
            return redirect()->route('entreprise.index');
        }
        else{
            $service = Activite::findOrFail($id);
            $service->delete();
            $service->travailler_users()->detach();

            if ($entreprise->activites()->count() === 0) {
                $entreprise->update(['publier' => 0]); 
            }

            return redirect()->route('entreprise.services.index', ['entreprise' => $entreprise->id])->with('success', 'Service supprimé avec succès.');
        }
    }
}
