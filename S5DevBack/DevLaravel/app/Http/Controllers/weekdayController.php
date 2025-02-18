<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Entreprise;
use App\Models\JourneeType;
use App\Models\SemaineType;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class WeekdayController extends Controller
{

    public function indexWeek(Request $request, Entreprise $entreprise)
    {
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;

        if($isAllow){
            if($entreprise->journeeTypes->count() == 0){
                return redirect()->route('entreprise.day.indexDay', ['entreprise' => $entreprise]);
            }
            return view('dayweektype.week', ['entreprise' => $entreprise]);
        }
        else {
            return redirect()->route('entreprise.show', ['entreprise' => $entreprise]);
        }
    }

    public function indexDay(Request $request, Entreprise $entreprise)
    {
        $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;
        $isCreator = $entreprise->idCreateur == Auth::user()->id;

        $isAllow = $isAdmin || $isCreator;

        if($isAllow){
            return view('dayweektype.day', ['entreprise' => $entreprise]);
        }
        else {
            return redirect()->route('entreprise.show', ['entreprise' => $entreprise]);
        }
    }

    public function ajaxWeek(Request $request, Entreprise $entreprise)
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

    public function ajaxDay(Request $request, Entreprise $entreprise)
    {
        switch ($request->type) {
            case 'get':
              $data = JourneeType::where('idEntreprise', $entreprise->id)->where("id",$request->idJournee)->get();
               return response()->json($data);
              break;


            case 'add':
                 $event = JourneeType::create([
                     'libelle' => $request->libelle,
                     'planning' => $request->planning,
                     'idEntreprise' => $entreprise->id,
                 ]);

                 $event = JourneeType::where('idEntreprise', $entreprise->id)->where("id",$event->id)->get();
               
               return response()->json($event);
              break;
   
            case 'update':
               $event = JourneeType::where("id",$request->idJournee)->first()->update([
                'libelle' => $request->libelle,
                'planning' => $request->planning,
               ]);
  
               return response()->json($event);
              break;
   
            case 'delete':
                $journee = JourneeType::where("id",$request->idJournee)->first();
                
                $event = $journee->delete();
   
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
