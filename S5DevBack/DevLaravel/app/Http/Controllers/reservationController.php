<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Reservation;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;

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
}
