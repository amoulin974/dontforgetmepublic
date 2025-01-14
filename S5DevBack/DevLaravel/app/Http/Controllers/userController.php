<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
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
            return view('user.index', [
                'utilisateur' => Auth::user()
            ]);
        }
        else{
            return redirect()->route('login');
        }
    }
}
