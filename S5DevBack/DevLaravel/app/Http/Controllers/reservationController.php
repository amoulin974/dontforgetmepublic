<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Reservation;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use App\Http\Requests\FormPostRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class reservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\View\View
     */
    public function index() : View
    {
        return view('reservation.index', [
            'reservations' => Reservation::simplePaginate(9)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Illuminate\View\View
     */
    public function show(Reservation $reservation) : View
    {
        return view('reservation.show', [
            'reservation' => $reservation
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $reservation = new Reservation();

        return view('reservation.create', [
            'reservation' => $reservation
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\FormPostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reservation = new Reservation($request->validated());
        $reservation->save();

        return redirect()->route('reservation.show', ['reservation' => $reservation->id])->with('success', 'La réservation a été ajoutée avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        // À modifier
        if((Auth::user()->id) || (Auth::user()->superadmin)) {
            return view('reservation.edit' , ['reservation' => $reservation]);        
        }
        else {
            return redirect()->route('reservation.index');
        }  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FormPostRequest  $request
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Reservation $reservation, Request $request)
    {
        $reservation->update($request->validated());

        return redirect()->route('reservation.show', ['reservation' => $reservation->id])->with('success', 'La réservation a été modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        $reservation = Reservation::findOrFail($reservation->id);

        if((Auth::user()->id) || (Auth::user()->superadmin)) {
            $reservation->delete();

            return redirect()->route('reservation.index')->with('success', 'Réservation supprimée avec succès');
        }
        else {
            return redirect()->route('reservation.index');
        }  
    }
}
