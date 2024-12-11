<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\reservationController;
use App\Http\Controllers\entrepriseController;

Route::prefix('/reservation')->name('reservation.')->controller(reservationController::class)->group(function(){

    Route::get('/', 'index')->name('index');

    Route::get('/{reservation}', 'show')->where([
        'id' => '[0-9]+',
    ])->name('show');

    Route::middleware(['auth'])->group(function () {
        Route::get('/new', 'create')->name('create');
        Route::post('/new', 'store');

        Route::get('/{reservation}/edit', 'edit')->name('edit');
        Route::post('/{reservation}/edit', 'update');

        Route::get('/{reservation}/delete', 'destroy')->name('delete');

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
