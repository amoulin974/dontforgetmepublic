<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Entreprise;
use App\Models\JourneeType;
use App\Models\SemaineType;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class weekdayController extends Controller
{

    public function indexWeek(Request $request, Entreprise $entreprise)
    {
      $isAdmin = Auth::user()->travailler_entreprises()->wherePivot('statut', 'Admin')->wherePivot('idEntreprise',$entreprise->id)->count() > 0;
      $isCreator = $entreprise->idCreateur == Auth::user()->id;

      $isAllow = $isAdmin || $isCreator;

      if($isAllow){
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
        case 'get':
          $data = SemaineType::where('idEntreprise', $entreprise->id)->where("id",$request->idSemaine)->get();
           return response()->json($data);
          break;
        
        case 'getDay':
          $data = JourneeType::where('idEntreprise', $entreprise->id)->where("id",$request->idJournee)->get();
            return response()->json($data);
          break;

        case 'add':
             $event = SemaineType::create([
                 'libelle' => $request->libelle,
                 'planning' => $request->planning,
                 'idEntreprise' => $entreprise->id,
             ]);

             $event = SemaineType::where('idEntreprise', $entreprise->id)->where("id",$event->id)->get();
           
           return response()->json($event);
          break;

        case 'update':
           $event = SemaineType::where("id",$request->idSemaine)->first()->update([
            'libelle' => $request->libelle,
            'planning' => $request->planning,
           ]);

           return response()->json($event);
          break;

        case 'delete':
            $semaine = SemaineType::where("id",$request->idSemaine)->first();
            
            $event = $semaine->delete();

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
              
            default:
              # code...
              break;
         }
    }
}
