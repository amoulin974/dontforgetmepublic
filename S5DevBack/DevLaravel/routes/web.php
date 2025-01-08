<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\reservationController;
use App\Http\Controllers\entrepriseController;
use App\Http\Controllers\calendrierController;
use App\Http\Controllers\parametrageController;

Route::prefix('/reservation')->name('reservation.')->controller(reservationController::class)->group(function(){

    Route::get('/', 'index')->name('index');

    Route::middleware(['auth'])->group(function () {
        Route::get('/new', 'create')->name('create');
        Route::post('/new', 'store')->name('store');

        Route::get('/{reservation}/edit', 'edit')->name('edit');
        Route::post('/{reservation}/edit', 'update')->name('update');

        Route::get('/{reservation}/delete', 'destroy')->name('delete');

        // Route::resources
    });

    Route::get('/{reservation}', 'show')->where([
        'id' => '[0-9]+',
    ])->name('show');
});

Route::prefix('/calendrier')->name('calendrier.')->controller(calendrierController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/ajax', 'ajax')->name('ajax');
    });
    
});

Route::prefix('/parametrage')->name('parametrage.')->controller(parametrageController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {

        Route::get('/', 'index')->name('index');

        Route::post('/invit', 'invit')->name('invit');
        
        Route::prefix('/plage')->name('plage.')->group(function(){
            Route::post('/', 'ajax')->name('ajax');
            Route::get('/{entreprise}', 'indexPlage')->name('idEntreprise');
        });

    });
    
});

Route::prefix('/entreprise')->name('entreprise.')->controller(entrepriseController::class)->group(function(){

    Route::get('/', 'index')->name('index');

    Route::get('/{entreprise}', 'show')->where([
        'id' => '[0-9]+',
    ])->name('show');
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
