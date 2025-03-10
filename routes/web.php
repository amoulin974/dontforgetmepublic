<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ParametrageController;
use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\ReserverController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeekdayController;
use App\Http\Controllers\BugReportController;

Route::post('/bug-report', [BugReportController::class, 'store'])->name('bug.report');
Route::prefix('/reservation')->name('reservation.')->controller(reservationController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{entreprise}/new/{activite}', 'create')->name('create');
        Route::post('/{entreprise}/new/{activite}', 'store')->name('store');
        Route::get('/{reservation}/edit', 'edit')->name('edit');
        Route::put('/{reservation}/edit', 'update')->name('update');
        Route::delete('/{reservation}', 'destroy')->name('destroy');
        Route::get('/{reservation}', 'show')->where([
            'id' => '[0-9]+',
        ])->name('show');
    });
});

Route::prefix('/parametrage')->name('parametrage.')->controller(ParametrageController::class)->group(function(){

    Route::middleware(['auth'])->group(function () {
        Route::post('/invit', 'invit')->name('invit');
        Route::prefix('/plage')->name('plage.')->group(function(){
            Route::post('/', 'ajax')->name('ajax');
            Route::get('/{entreprise}', 'indexPlage')->name('idEntreprise');
            Route::get('/{entreprise}/look/{employe}', 'indexPlageAsEmploye')->name('idEntrepriseAsEmploye');
        });

    });

});

Route::prefix('/entreprise')->name('entreprise.')->controller(EntrepriseController::class)->group(function(){
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

        Route::get('/{entreprise}/edit', 'edit')->where([
            'id' => '[0-9]+',
        ])->name('edit');

        Route::put('/{entreprise}', 'update')->where([
            'id' => '[0-9]+',
        ])->name('update');

        Route::get('/{entreprise}/activites', 'showActivites')->where([
            'id' => '[0-9]+',
        ])->name('activites');

        Route::prefix('{entreprise}/week')->name('week.')->controller(WeekdayController::class)->group(function() {
            Route::get('/', 'indexWeek')->name('indexWeek');
            Route::post('/', 'ajaxWeek')->name('ajaxWeek');
        });

        Route::prefix('{entreprise}/day')->name('day.')->controller(WeekdayController::class)->group(function() {
            Route::get('/', 'indexDay')->name('indexDay');
            Route::post('/', 'ajaxDay')->name('ajaxDay');
        });

        Route::prefix('{entreprise}/services')->name('services.')->controller(ActiviteController::class)->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}', 'show')->name('show');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
            Route::get('/{employe}/plage', 'createPlage')->name('createPlage');
            Route::post('/{employe}/plage', 'ajaxPlage')->name('ajaxPlage');
        });
    });
});

Route::prefix('/reserver')->name('reserver.')->controller(ReserverController::class)->group(function(){

    Route::get('/', 'index')->name('index');
});

Route::prefix('/profile')->name('profile.')->controller(UserController::class)->group(function(){
    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
    });
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('welcome');

Auth::routes();

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
