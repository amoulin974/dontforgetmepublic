<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * @brief Controller for managing user profile operations.
 *
 * This controller handles displaying the user profile, editing the profile form, and updating the user's information.
 */
class userController extends Controller
{
    /**
     * Display the user profile index view.
     *
     * This method checks if the user is authenticated. If the user is logged in, it retrieves the enterprises
     * where the authenticated user is the creator and paginates the results (9 per page). It then returns the
     * "user.index" view along with the user's data and the retrieved enterprises. If the user is not authenticated,
     * the method redirects to the login route.
     *
     * @return \Illuminate\View\View The view displaying the user's profile and related enterprises.
     */
    public function index() : View
    {
        if (Auth::check()) {
            $entreprises = Entreprise::where('idCreateur', Auth::user()->id)->paginate(9);
            return view('user.index', [
                'utilisateur' => Auth::user(),
            ], compact('entreprises'));
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Show the form for editing the authenticated user's profile.
     *
     * This method returns the profile edit view with the currently authenticated user's data.
     *
     * @return \Illuminate\View\View The view for editing the user's profile.
     */
    public function edit() : View
    {
        return view('user.edit', ['user' => Auth::user()]);
    }

    /**
     * Update the authenticated user's profile information in storage.
     *
     * This method validates the incoming request data, updates the authenticated user's profile with the validated data,
     * and then redirects back to the profile index with a success message.
     *
     * @param Request $request The HTTP request containing the profile update data.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the profile index route with a success message.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the submitted form data.
        $validatedData = $request->validate([
            'nom'             => ['required', 'string', 'max:255'],
            'prenom'          => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'numTel'          => ['nullable', 'string', 'max:20', 'regex:/^(\d{2} \d{2} \d{2} \d{2} \d{2}|\d{10})$/'],
            'typeNotif'       => ['nullable', 'in:SMS,Email'],
            'delaiAvantNotif' => ['nullable', 'in:1 jour,2 jours,1 semaine'],
        ]);

        // Update the user's information using the validated data.
        $user->update([
            'nom'             => $validatedData['nom'],
            'prenom'          => $validatedData['prenom'],
            'email'           => $validatedData['email'],
            'numTel'          => $validatedData['numTel'],
            'typeNotif'       => $validatedData['typeNotif'] ?? null,
            'delaiAvantNotif' => $validatedData['delaiAvantNotif'] ?? null,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profil mis à jour avec succès.');
    }
}
