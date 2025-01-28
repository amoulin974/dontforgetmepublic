<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        if(Auth::check()){
            $entreprises = Entreprise::where('idCreateur', Auth::user()->id)->paginate(9);
            return view('user.index', [
                'utilisateur' => Auth::user(),
            ], compact('entreprises'));
        }
        else{
            return redirect()->route('login');
        }
    }
}
