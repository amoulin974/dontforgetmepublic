<?php
/**
 * @file ReserverController.php
 * @brief Controller for handling enterprise reservation functionalities.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * @class ReserverController
 * @brief Controller for handling enterprise reservation functionalities.
 *
 * This controller provides methods for displaying a list of published enterprises
 * available for reservation. It also includes stub methods for creating, storing,
 * showing, editing, updating, and deleting reservation-related resources.
 */
class ReserverController extends Controller
{
    /**
     * Display a listing of the published enterprises available for reservation.
     *
     * This method retrieves only the enterprises marked as published and paginates
     * the results (9 enterprises per page). It then returns the "reserver.index" view
     * with the retrieved data.
     *
     * @return \Illuminate\View\View The view displaying the list of published enterprises.
     */
    public function index()
    {
        // Retrieve only published enterprises and paginate them to 9 per page.
        $entreprises = Entreprise::where('publier', true)->paginate(9);
        return view('reserver.index', compact('entreprises'));
    }
}