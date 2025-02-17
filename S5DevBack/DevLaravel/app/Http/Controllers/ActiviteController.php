<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activite;
use App\Models\Entreprise;
use App\Models\Plage;
use App\Models\User;
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
        // \Log::info('Méthode HTTP utilisée : ' . $request->method());
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;

        if(!$isAllow){
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

          // Ajouter les employés de l'entreprise à l'activité
            $entreprise->travailler_users()->where('statut', 'Employé')->get()->each(function ($user) use ($activite, $entreprise) {
                $activite->travailler_users()->attach($user->id, [
                    'idEntreprise' => $entreprise->id,
                    'statut' => 'Employé',
                ]);
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
                'duree' => 'required|integer|min:1', // Durée en minutes
            ]);
    
            $service = Activite::findOrFail($id);
    
            // Convertir la durée (minutes) en format H:i:s
            $dureeInTimeFormat = gmdate('H:i:s', $request->duree * 60);
            //dd($dureeInTimeFormat);
    
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

    public function createPlage(Request $request, Entreprise $entreprise, User $employe)
    {
        // Pour récupérer les données
        if($request->ajax()) {
        // Cas employé
        if(Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut != 'Invité') {
          // Requête pour récupérer les plages spécifique à l'employé et à l'entreprise choisie
          $plages = User::where('id', $employe->id)->first() ->plages()->where('entreprise_id', $entreprise->id)->get();
            if ($plages) {
                // Ajout des activités liées à chacune des plages
                foreach ($plages as $plage) {
                    $plage->activites = $plage->activites()->get();
                }
                return response()->json($plages);
            } else {
                // Handle the case where the activite is not found
                return response()->json(['error' => 'Plages not found'], 404);
            }
        }
        else {
            return view('plage.create', ['entreprise' => $entreprise, 'employe' => $employe]);
        }
      }
        return view('plage.create', ['entreprise' => $entreprise, 'employe' => $employe]);
    }

    public function ajaxPlage(Request $request, Entreprise $entreprise, User $employe)
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
                     'entreprise_id' => $entreprise->id,
                 ]);
               }
               else {
                 $event = Plage::create([
                     'heureDeb' => $request->heureDeb,
                     'heureFin' => $request->heureFin,
                     'datePlage' => $request->datePlage,
                     'interval' => $request->interval,
                     'planTables' => json_encode(['UnPlanDeTables']),
                     'entreprise_id' => $entreprise->id,
                 ]);
               }

               $event->employes()->attach($employe->id);

               foreach($request->activites_affecter as $id){
                    $event->activites()->attach($id);
                }

                $event->activites = $event->activites()->get();
               
               return response()->json($event);
              break;
   
            case 'update':
               $event = Plage::where("id",$request->id)->first()->update([
                 'heureDeb' => $request->heureDeb,
                 'heureFin' => $request->heureFin,
                 'datePlage' => $request->datePlage,
               ]);
  
               return response()->json($event);
              break;
   
            case 'delete':
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

               /* foreach($request->activites_affecter as $idEmploye){
                    $event->employes()->attach($idEmploye);
                }
   */
                $event->activites = $event->activites()->get();

               return response()->json($event);
              break;
              
            default:
              # code...
              break;
         }
    }
}
