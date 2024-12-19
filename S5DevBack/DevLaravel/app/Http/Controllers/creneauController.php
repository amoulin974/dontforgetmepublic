<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Creneau;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class creneauController extends Controller
{

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(Request $request)
    {
  
        if($request->ajax()) {
       
             $data = Creneau::whereDate('heureDeb', '>=', $request->heureFin)
                       ->whereDate('heureFin',   '<=', $request->heureDeb)
                       ->get(['id', 'heureDeb', 'heureFin', 'dateC']);
  
             return response()->json($data);
        }
  
        return view('creneau.index', [
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
              $event = Creneau::create([
                  'heureDeb' => $request->heureDeb,
                  'heureFin' => $request->heureFin,
                  'dateC' => $request->dateC,
              ]);
 
              return response()->json($event);
             break;
  
           case 'update':
              $event = Creneau::find($request->id)->update([
                'heureDeb' => $request->heureDeb,
                'heureFin' => $request->heureFin,
                'dateC' => $request->dateC,
              ]);
 
              return response()->json($event);
             break;
  
           case 'delete':
              $event = Creneau::find($request->id)->delete();
  
              return response()->json($event);
             break;
             
           default:
             # code...
             break;
        }
    }
}