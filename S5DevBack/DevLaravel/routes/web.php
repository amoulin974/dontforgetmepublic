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
});

Route::prefix('/entreprise')->name('entreprise.')->controller(entrepriseController::class)->group(function(){

    Route::get('/', 'index')->name('index');

    Route::get('/{entreprise}', 'show')->where([
        'id' => '[0-9]+',
    ])->name('show');
});

Route::get('/', function () {
    return view('layouts.app');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
