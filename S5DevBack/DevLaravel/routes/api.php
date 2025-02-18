<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\LoginController;

Route::post('/login', [LoginController::class, 'tokenLogin']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Groupe de routes sécurisées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/details', [NotificationController::class, 'getDetails']);
    Route::patch('/details/{id}', [NotificationController::class, 'updateNotificationState']);
    Route::delete('/details/{id}', [NotificationController::class, 'destroy']);
});
