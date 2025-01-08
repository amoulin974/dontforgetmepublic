<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Reservation;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class calendrierController extends Controller
{

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
             $data = Reservation::/* whereDate('heureDeb', '>=', $request->heureFin)
                       ->whereDate('heureFin',   '<=', $request->heureDeb)
                       -> */get(['id', 'heureDeb', 'heureFin', 'dateRdv', 'nbPersonnes']);
             return response()->json($data);
        }
  
        return view('calendrier.index', [
            'user' => Auth::user()
        ]);
    }
 
    /**
     * Write code on Method
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
              if (!$request->nbPersonnes){
                $event = Reservation::create([
                    'heureDeb' => $request->heureDeb,
                    'heureFin' => $request->heureFin,
                    'dateRdv' => $request->dateRdv,
                    'nbPersonnes' => 1,
                ]);
              }
              else {
                $event = Reservation::create([
                    'heureDeb' => $request->heureDeb,
                    'heureFin' => $request->heureFin,
                    'dateRdv' => $request->dateRdv,
                    'nbPersonnes' => $request->nbPersonnes,
                ]);
              }
              
              return response()->json($event);
             break;
  
           case 'update':
              $event = Reservation::find($request->id)->update([
                'heureDeb' => $request->heureDeb,
                'heureFin' => $request->heureFin,
                'dateRdv' => $request->dateRdv,
              ]);
 
              return response()->json($event);
             break;
  
           case 'delete':
              $event = Reservation::find($request->id)->delete();
  
              return response()->json($event);
             break;

           case 'modify':
              $event = Reservation::find($request->id)->update([
                'nbPersonnes' => $request->nbPersonnes,
              ]);
 
              return response()->json($event);
             break;

           /* case 'get':
                $data = reservation::whereDate('heureDeb', '>=', $request->heureFin)
                       ->whereDate('heureFin',   '<=', $request->heureDeb)
                       ->get(['id', 'heureDeb', 'heureFin', 'dateC']);
                return response()->json($data);
             break; */
             
           default:
             # code...
             break;
        }
    }
}