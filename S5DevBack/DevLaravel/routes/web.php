<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\reservationController;
use App\Http\Controllers\entrepriseController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\calendrierController;
use App\Http\Controllers\parametrageController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\ReserverController;
use App\Http\Controllers\userController;

Route::prefix('/reservation')->name('reservation.')->controller(reservationController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{entreprise}/new/{activite}', 'create')->name('create');
        Route::post('/{entreprise}/new/{activite}', 'store')->name('store');

        Route::get('/{reservation}/edit', 'edit')->name('edit');
        Route::put('/{reservation}/edit', 'update')->name('update');
        // Route::resources

        Route::delete('/{reservation}', 'destroy')->name('destroy');

        // Route::resources

        Route::get('/{reservation}', 'show')->where([
            'id' => '[0-9]+',
        ])->name('show');
    });
});

Route::prefix('/calendrier')->name('calendrier.')->controller(calendrierController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/ajax', 'ajax')->name('ajax');
    });    
});

Route::prefix('/parametrage')->name('parametrage.')->controller(parametrageController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {

        /* Route::get('/', 'index')->name('index'); */

        Route::post('/invit', 'invit')->name('invit');

        Route::prefix('/plage')->name('plage.')->group(function(){
            Route::post('/', 'ajax')->name('ajax');
            Route::get('/{entreprise}', 'indexPlage')->name('idEntreprise');
            Route::get('/{entreprise}/look/{activite}', 'indexPlageAsEmploye')->name('idEntrepriseAsEmploye');
        });

    });

});

Route::prefix('/entreprise')->name('entreprise.')->controller(entrepriseController::class)->group(function(){
    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'indexUser')->name('indexUser');
        Route::get('/create', 'create')->name('create');
        Route::post('/type-rdv', 'showTypeRdvPage')->name('typeRdv');
        Route::get('/recap', 'showRecapPage')->name('recap');
        Route::post('/submit-appointments', 'storeAppointments')->name('submit.appointments');
        Route::post('/store', 'store')->name('store');

        Route::post('/{entreprise}', 'ajax')->where([
            'id' => '[0-9]+',
        ])->name('ajax');
        Route::get('/{entreprise}', 'show')->where([
            'id' => '[0-9]+',
        ])->name('show');

        Route::get('/{entreprise}/activites', 'showActivites')->where([
            'id' => '[0-9]+',
        ])->name('activites');

        Route::prefix('{entreprise}/services')->name('services.')->controller(ActiviteController::class)->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/{id}/plage', 'createPlage')->name('createPlage');
            Route::post('/{id}/plage', 'ajaxPlage')->name('ajaxPlage');
        });
    });
});

Route::prefix('/reserver')->name('reserver.')->controller(ReserverController::class)->group(function(){

    Route::get('/', 'index')->name('index');
});

Route::prefix('/profile')->name('profile.')->controller(userController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'index')->name('index');
    });
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('welcome');

Auth::routes();

/*
Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->middleware('throttle:3,1');
*/

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/clear-session', function () {    
    session()->forget(['company', 'appointment']);
    return redirect()->route('home');
})->name('clear.session');


Route::prefix('/register')->name('register.')->controller(RegisterController::class)->group(function() {
    Route::get('/choose-account-type', [RegisterController::class, 'showChoicePage'])->name('choose.account.type');

    Route::get('/user', [RegisterController::class, 'showUserRegisterPage'])->name('user.register');
    
    Route::get('/userAccount', [RegisterController::class, 'showUserRegisterPage'])->name('company.register.user');

    Route::post('/user', 'storeUser')->name('user.store');
});
