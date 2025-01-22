<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Plage;
use App\Models\Activite;
use App\Models\Entreprise;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class parametrageController extends Controller
{

    /**
     * Méthode index pour afficher la vue principale (paramétrage)
     *
     * @return response()
     */
    public function index()
    {
        // Vérification utilisateur travaille
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        else if (Auth::user()->travailler_entreprises->isEmpty()) {
            return redirect()->route('home');
        }
        else {
            return view('parametrage.index', [
                'user' => Auth::user(),
            ]);
        }
    }

    /**
     * Méthode index pour afficher la vue principale (planification des plages)
     *
     * @return response()
     */
    public function indexPlage(Request $request, Entreprise $entreprise)
    {
      // Pour récupérer les données
      if($request->ajax()) {
        // Cas employé
        if(Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Employé') {
          // Requête pour récupérer les plages
          $data = Plage::where('entreprise_id', $entreprise->id)->get(['id', 'heureDeb', 'heureFin', 'datePlage', 'interval']);
          return response()->json($data);
        }
        elseif(Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin') {
          // Requête pour récupérer les plages
          $data = Plage::where('entreprise_id', $entreprise->id)->get(['id', 'heureDeb', 'heureFin', 'datePlage', 'interval']);
          return response()->json($data);
        }
      }
      // Vérification utilisateur travaille
      if (!Auth::check()) {
          return redirect()->route('login');
      }
      else if (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->isEmpty()) {
        return redirect()->route('parametrage.index');
      }
      else {
        if(Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Employé') {
          // Sinon on renvoie la vue employé
          return view('plage.show', [
            'user' => Auth::user(),
            'entreprise' => $entreprise,
          ]);
        }
        elseif(Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin') {
          // Sinon on renvoie la vue admin
          return view('plage.index', [
              'user' => Auth::user(),
              'entreprise' => $entreprise,
          ]);
        }
        else {
          // Sinon erreur normalement non atteint
          return redirect()->route('parametrage.index');
        }
      }
    }

    /**
     * Méthode index pour afficher la vue principale (planification des plages)
     *
     * @return response()
     */
    public function indexPlageAsEmploye(Request $request, Entreprise $entreprise, Activite $activite)
    {
      // Pour récupérer les données
      if($request->ajax()) {
        if(Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut != 'Invité') {
          // à rajouter : récupérer les plages spécifiques à l'utilisateur

          // Requête pour récupérer les plages spécifique à l'activité et à l'entreprise choisie
          $activites = Activite::where('id', $activite->id)->where('idEntreprise', $entreprise->id)->first();

            if ($activites) {
                $plageIds = $activites->plages()->pluck('idPlage');
                $data = Plage::whereIn('id', $plageIds)->get(['id', 'heureDeb', 'heureFin', 'datePlage', 'interval']);
                return response()->json($data);
            } else {
                // Handle the case where the activite is not found
                return response()->json(['error' => 'Activite not found'], 404);
            }
        }
        /* // Cas employé
        if(Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Employé') {
          // Requête pour récupérer les plages
          $data = Plage::where('entreprise_id', $entreprise->id)->get(['id', 'heureDeb', 'heureFin', 'datePlage', 'interval']);
          return response()->json($data);
        }
        elseif(Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin') {
          // Requête pour récupérer les plages
          $data = Plage::where('entreprise_id', $entreprise->id)->get(['id', 'heureDeb', 'heureFin', 'datePlage', 'interval']);
          return response()->json($data);
        } */
      }
      // Vérification utilisateur travaille
      if (!Auth::check()) {
          return redirect()->route('login');
      }
      else if (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->isEmpty()) {
        return redirect()->route('parametrage.index');
      }
      else {
        if(Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Employé' || Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin') {
          // Sinon on renvoie la vue admin
          return view('plage.show', [
              'user' => Auth::user(),
              'entreprise' => $entreprise,
              'activite' => $activite,
          ]);
        }
        else {
          // Sinon erreur normalement non atteint
          return redirect()->route('parametrage.index');
        }
      }
    }

    /**
     * Méthode invit qui traite la réponse à une invitation
     *
     * @return response()
     */
    public function invit(Request $request)
    {
        switch ($request->type) {
           case 'accept':
              // Envoyer mail ou sms
            
              // Update le pivot pour changer le status dans travailler
              // Si demande admin
              /* if ($request->statut == 'Admin') {
                // Update en conséquence
                Auth::user()->travailler_entreprises->where('id', $request->entreprise_id)->first()->pivot->update([
                  'statut' => 'Admin',
                ]);
              }
              else { */
                // Update en conséquence
                $event = Auth::user()->travailler_entreprises->where('id', $request->idEntreprise)->first()->pivot->update([
                  'statut' => 'Employé',
                ]);
              /* } */

              return response()->json($event);//redirect()->route('parametrage.plage.idEntreprise', ['entreprise' => $request->idEntreprise]);
             break;
  
           case 'reject':
              // Envoyer mail ou sms
              // Supprimer du pivot travailler
              $event = Auth::user()->travailler_entreprises->where('id', $request->idEntreprise)->first()->pivot->delete();
              return response()->json($event);//redirect()->route('entreprise.indexUser');
             break;
             
           default:
              return redirect()->route('entreprise.indexUser');
             break;
        }
    }
 
    /**
     * Méthode ajax pour ajouter, modifier, mettre à jour ou supprimer une plage
     *
     * @return response()
     */
    public function ajax(Request $request)
    {
        switch ($request->type) {
           case 'add':
              /* if ($request->heureDeb > $request->heureFin) {
                  return response()->json('error');
              } */
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

              //$event->activites()->attach($idActivites);
              
              return response()->json($event);
             break;
  
           case 'update':
              $event = Plage::find($request->id)->update([
                'heureDeb' => $request->heureDeb,
                'heureFin' => $request->heureFin,
                'datePlage' => $request->datePlage,
              ]);
 
              return response()->json($event);
             break;
  
           case 'delete':

              $event = Plage::findOrFail($request->id)->first();

              // Supprimer les relations
              $event->activites()->detach();

              $event = $event->delete();
  
              return response()->json($event);
             break;

           case 'modify':
              $event = Plage::find($request->id)->update([
                'interval' => $request->interval,
              ]);
 
              return response()->json($event);
             break;
             
           default:
             # code...
             break;
        }
    }
}
