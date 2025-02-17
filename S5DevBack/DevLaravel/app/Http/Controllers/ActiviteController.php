<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activite;
use App\Models\Entreprise;
use App\Models\Plage;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ActiviteController extends Controller
{
    public function index(Entreprise $entreprise)
    {
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;
        $isEmploye = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Employé')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator || $isEmploye;

        if($isAllow){
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
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;
        if(!$isAllow){
            return redirect()->route('entreprise.index');
        }else {
            return view('activite.create', ['entreprise' => $entreprise]);
        }
    }

    public function store(Request $request, Entreprise $entreprise)
    {
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;

        if(!$isAllow){
            return redirect()->route('entreprise.index');
        } else {
            $request->validate([
                'libelle' => 'required|string|max:255',
                'duree' => 'required|date_format:H:i', // Validation pour le format time
            ]);

            // Conversion de la durée du format time (HH:MM) en minutes
            list($heures, $minutes) = explode(':', $request->duree);
            $dureeEnMinutes = ($heures * 60) + $minutes;

            $dureeInTimeFormat = gmdate('H:i:s', $dureeEnMinutes * 60);

            $activite = Activite::create([
                'libelle' => $request->libelle,
                'duree' => $dureeInTimeFormat,
                'idEntreprise' => $entreprise->id
            ]);

            $activite->travailler_users()->attach(auth()->id(), [
                'idEntreprise' => $entreprise->id,
                'statut' => 'Admin',
            ]);

            // Ajouter les employés de l'entreprise à l'activité
            $entreprise->travailler_users()->where('statut', 'Employé')->get()->each(function ($user) use ($activite, $entreprise) {
                $activite->travailler_users()->syncWithoutDetaching([$user->id => [
                    'idEntreprise' => $entreprise->id,
                    'statut' => 'Employé',
                ]]);
            });
            $entreprise->travailler_users()->where('statut', 'Admin')->get()->each(function ($user) use ($activite, $entreprise) {
                if ($user->id != Auth::user()->id) {
                    $activite->travailler_users()->attach($user->id, [
                        'idEntreprise' => $entreprise->id,
                        'statut' => 'Admin',
                    ]);
                }
            });

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
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;
        if(!$isAllow){
            return redirect()->route('entreprise.index');
        }
        else{
            $service = Activite::where('id', $id)->where('idEntreprise', $entreprise->id)->firstOrFail();
        return view('activite.edit', ['entreprise' => $entreprise, 'service' => $service]);
        }
    }

    public function update(Request $request, Entreprise $entreprise, $id)
    {
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;
        if(!$isAllow){
            return redirect()->route('entreprise.index');
        }
        else{
            $request->validate([
                'libelle' => 'required|string|max:255',
                'duree' => 'required|date_format:H:i', // Durée en minutes
            ]);
    
            $service = Activite::findOrFail($id);
    
            // Conversion de la durée du format time (HH:MM) en minutes
            list($heures, $minutes) = explode(':', $request->duree);
            $dureeEnMinutes = ($heures * 60) + $minutes;

            $dureeInTimeFormat = gmdate('H:i:s', $dureeEnMinutes * 60);
    
            $service->update([
                'libelle' => $request->libelle,
                'duree' => $dureeInTimeFormat,
            ]);
    
            return redirect()->route('entreprise.services.index', ['entreprise' => $entreprise->id])->with('success', 'Service mis à jour avec succès.');
        }
    }

    public function destroy(Entreprise $entreprise, $id)
    {
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;
        if(!$isAllow){
            return redirect()->route('entreprise.index');
        }
        else{
            $service = Activite::findOrFail($id);
            $service->travailler_users()->detach();
            $service->plages()->detach();
            $service->travailler_entreprises()->detach();
            $service->delete();

            if ($entreprise->activites()->count() === 0) {
                $entreprise->update(['publier' => 0]); 
            }

            return redirect()->route('entreprise.services.index', ['entreprise' => $entreprise->id])->with('success', 'Service supprimé avec succès.');
        }
    }

    public function createPlage(Request $request, Entreprise $entreprise, $id)
    {
        // Pour récupérer les données
        if($request->ajax()) {
        // Cas employé
        if(Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut != 'Invité') {
          // Requête pour récupérer les plages spécifique à l'activité et à l'entreprise choisie
          $activite = Activite::where('id', $id)->where('idEntreprise', $entreprise->id)->first();

            if ($activite) {
                $plageIds = $activite->plages()->pluck('idPlage');
                $data = Plage::whereIn('id', $plageIds)->get(['id', 'heureDeb', 'heureFin', 'datePlage', 'interval']);
                return response()->json($data);
            } else {
                // Handle the case where the activite is not found
                return response()->json(['error' => 'Activite not found'], 404);
            }
        }
        else {
            $service = Activite::findOrFail($id);
            return view('plage.create', ['entreprise' => $entreprise, 'activite' => $service]);
        }
      }
        $service = Activite::findOrFail($id);
        return view('plage.create', ['entreprise' => $entreprise, 'activite' => $service]);
    }

    public function ajaxPlage(Request $request, Entreprise $entreprise, $id)
    {
        switch ($request->type) {
            case 'add':
               if (!$request->interval){
                 $event = Plage::create([
                     'heureDeb' => $request->heureDeb,
                     'heureFin' => $request->heureFin,
                     'datePlage' => $request->datePlage,
                     'interval' => '00:05:00',
                     'planTables' => json_encode(['UnTest']),
                     'entreprise_id' => $request->entreprise_id,
                 ]);
               }
               else {
                 $event = Plage::create([
                     'heureDeb' => $request->heureDeb,
                     'heureFin' => $request->heureFin,
                     'datePlage' => $request->datePlage,
                     'interval' => $request->interval,
                     'planTables' => json_encode(['UnPlanDeTables']),
                     'entreprise_id' => $request->entreprise_id,
                 ]);
               }

               //Auth::user()->travailler_activites()->attach($id, ['idEntreprise'=>$entreprise->id,'statut' => 'Admin']);
               foreach($request->employes_affecter as $idEmploye){
                   $event->employes()->attach($idEmploye);
               }

               $event->activites()->attach($id);
               
               return response()->json($event);
              break;
   
            case 'update':
               $event = Plage::where($request->id)->first()->update([
                 'heureDeb' => $request->heureDeb,
                 'heureFin' => $request->heureFin,
                 'datePlage' => $request->datePlage,
               ]);
  
               return response()->json($event);
              break;
   
            case 'delete':
                $activite = Activite::where("id",$id)->first();
                $plage = Plage::where("id",$request->id)->first();
                $plage->employes()->detach();
                $plage->activites()->detach();
                $event = $plage->delete();
   
               return response()->json($event);
              break;
 
            case 'modify':
               /* $event = Plage::find($request->id)->update([
                 'interval' => $request->interval,
               ]); */

               $event = Plage::where("id",$request->id)->first();

               $event->employes()->detach();

               foreach($request->employes_affecter as $idEmploye){
                    $event->employes()->attach($idEmploye);
                }
  
               return response()->json($event);
              break;
              
            default:
              # code...
              break;
         }
    }
}
