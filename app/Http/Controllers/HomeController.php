<?php
/**
 * @file HomeController.php
 * @brief Controller responsible for handling the application's home page.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entreprise;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * @class HomeController
 * @brief Controller responsible for handling the application's home page.
 *
 * This controller manages the display of the main dashboard (welcome page),
 * which shows the list of published enterprises.
 */
class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * This method retrieves enterprises that are marked as published and paginates them,
     * then returns the welcome view with the retrieved data.
     *
     * @return \Illuminate\Contracts\Support\Renderable Returns a view instance for the welcome page.
     */
    public function index()
    {
        $entreprises = Entreprise::where('publier', true)->paginate(9);
        return view('welcome', compact('entreprises'));
    }
}
