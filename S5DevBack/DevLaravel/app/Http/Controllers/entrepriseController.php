<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Entreprise;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class entrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\View\View
     */
    public function index() : View
    {
        return view('entreprise.index', [
            'entreprises' => Entreprise::simplePaginate(9)
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\View\View
     */
    public function indexUser() : View
    {
        if (Auth::user()->travailler_entreprises->where('id', $entreprise->id)->first()->pivot->statut == 'Admin' || !Entreprise::where('idCreateur', Auth::user()->id)->isEmpty()) {
            return view('entreprise.index', [
                'entreprises' => Entreprise::where('idCreateur', Auth::user()->id)->simplePaginate(9)
            ]);
        }
        else {
            return redirect()->route('home');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Entreprise $entreprise
     * @return Illuminate\View\View
     */
    public function show(Entreprise $entreprise) : View
    {
        return view('entreprise.show', [
            'entreprise' => $entreprise
        ]);
    }
}
