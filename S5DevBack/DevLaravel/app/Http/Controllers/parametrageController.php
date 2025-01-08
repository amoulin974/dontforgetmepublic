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
        // Requête pour récupérer les plages
        $data = Plage::where('entreprise_id', $entreprise->id)->get(['id', 'heureDeb', 'heureFin', 'datePlage', 'interval']);
        return response()->json($data);
      }
      
      // Vérification utilisateur travaille
      if (!Auth::check()) {
          return redirect()->route('login');
      }
      else if (Auth::user()->travailler_entreprises->isEmpty()) {
          return redirect()->route('home');
      }
      else {
        // Sinon on renvoie la vue
        return view('plage.index', [
            'user' => Auth::user(),
            'entreprise' => $entreprise,
        ]);
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
              $event = Plage::find($request->id)->delete();
  
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
