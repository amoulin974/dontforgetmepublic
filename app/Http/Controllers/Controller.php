<?php
/**
 * @file Controller.php
 * @brief Base controller for handling common operations in the Laravel application.
 */
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @class Controller
 * @brief Base controller for handling common operations in the Laravel application.
 * 
 * This class provides authorization and validation functionalities for controllers in the application. 
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
