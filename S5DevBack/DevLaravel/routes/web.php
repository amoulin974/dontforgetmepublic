<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\reservationController;
use App\Http\Controllers\entrepriseController;
use App\Http\Controllers\Auth\RegisterController;

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

Route::prefix('/register')->name('register.')->controller(RegisterController::class)->group(function() {
    Route::get('/choose-account-type', [RegisterController::class, 'showChoicePage'])->name('choose.account.type');
    
    Route::get('/user', [RegisterController::class, 'showUserRegisterPage'])->name('user.register');
    
    Route::get('/company/userAccount', [RegisterController::class, 'showUserRegisterPage'])->name('company.register.user');

    //Route::post('/company/companyAccount', [RegisterController::class, 'showCompanyRegisterPage'])->name('company.register.company');
    Route::match(['get', 'post'], '/company/companyAccount', [RegisterController::class, 'showCompanyRegisterPage'])->name('company.register.company');

    Route::post('/company/typeRdv', [RegisterController::class, 'showTypeRdvPage'])->name('company.register.typeRdv');

    Route::get('/company/recap', [RegisterController::class, 'showRecapPage'])->name('company.register.recap');

    Route::post('/submit-responses', [RegisterController::class, 'storeResponses'])->name('submit.responses');

    Route::post('/company/submit', [RegisterController::class, 'submit'])->name('company.register.submit');
});

