<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BugReportMail;

class BugReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|min:10',
        ]);

        // Envoi de l'e-mail au support
        Mail::to('johanrouyer2@gmail.com')->send(new BugReportMail($request->description));

        return back()->with('success', 'Votre rapport de bug a été envoyé avec succès.');
    }
}
