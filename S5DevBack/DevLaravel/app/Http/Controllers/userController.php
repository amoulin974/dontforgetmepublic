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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit() : View
    {
        return view('user.edit', ['user' => Auth::user()]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation des données du formulaire
        $validatedData = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'numTel' => ['nullable', 'string', 'max:20', 'regex:/^(\d{2} \d{2} \d{2} \d{2} \d{2}|\d{10})$/'],
            'typeNotif' => ['nullable', 'in:SMS,Email'],
            'delaiAvantNotif' => ['nullable', 'in:1 jour,2 jours,1 semaine'],
        ]);

        // Mise à jour des informations utilisateur
        $user->update([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'email' => $validatedData['email'],
            'numTel' => $validatedData['numTel'],
            'typeNotif' => $validatedData['typeNotif'] ?? null,
            'delaiAvantNotif' => $validatedData['delaiAvantNotif'] ?? null,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profil mis à jour avec succès.');
    }
}
