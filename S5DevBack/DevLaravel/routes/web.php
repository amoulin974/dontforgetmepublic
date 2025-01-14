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
        Route::post('/{reservation}/edit', 'update')->name('update');

        Route::get('/{reservation}/delete', 'destroy')->name('delete');

        // Route::resources
    });

    Route::get('/{reservation}', 'show')->where([
        'id' => '[0-9]+',
    ])->name('show');
});

/* Route::prefix('/calendrier')->name('calendrier.')->controller(calendrierController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/ajax', 'ajax')->name('ajax');
    });
    
}); */

Route::prefix('/parametrage')->name('parametrage.')->controller(parametrageController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {

        Route::get('/', 'index')->name('index');

        Route::post('/invit', 'invit')->name('invit');
        
        Route::prefix('/plage')->name('plage.')->group(function(){
            Route::post('/', 'ajax')->name('ajax');
            Route::get('/{entreprise}', 'indexPlage')->name('idEntreprise');
            Route::get('/{entreprise}/look', 'indexPlageAsEmploye')->name('idEntrepriseAsEmploye');
        });

    });
    
});

Route::prefix('/entreprise')->name('entreprise.')->controller(entrepriseController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'indexUser')->name('indexUser');
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
    
    //Route::get('/', 'index')->name('index');

    
});

Route::prefix('/reserver')->name('reserver.')->controller(ReserverController::class)->group(function(){

    Route::get('/', 'index')->name('index');
});

Route::prefix('/profile')->name('profile.')->controller(userController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'index')->name('index');
    });
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('/register')->name('register.')->controller(RegisterController::class)->group(function() {
    Route::get('/choose-account-type', [RegisterController::class, 'showChoicePage'])->name('choose.account.type');
    
    Route::get('/user', [RegisterController::class, 'showUserRegisterPage'])->name('user.register');
    
    Route::get('/company/userAccount', [RegisterController::class, 'showUserRegisterPage'])->name('company.register.user');

    Route::match(['get', 'post'], '/company/companyAccount', [RegisterController::class, 'showCompanyRegisterPage'])->name('company.register.company');

    Route::post('/company/typeRdv', [RegisterController::class, 'showTypeRdvPage'])->name('company.register.typeRdv');

    Route::get('/company/recap', [RegisterController::class, 'showRecapPage'])->name('company.register.recap');

    Route::post('/submit-responses', [RegisterController::class, 'storeResponses'])->name('submit.responses');

    Route::post('/company/submit', [RegisterController::class, 'submit'])->name('company.register.submit');
});