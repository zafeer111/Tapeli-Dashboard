<?php

use App\Enums\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BundleController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\SportController;
use App\Http\Controllers\Api\TournamentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/reset', [AuthController::class, 'passwordResetRequest']);
Route::post('/password/reset/confirm', [AuthController::class, 'passwordResetConfirm']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);


    Route::put('/profile', [ProfileController::class, 'update']);

    // Tournaments Module
    Route::get('/tournaments', [TournamentController::class, 'index']);
    Route::post('/tournaments', [TournamentController::class, 'store']);
    Route::put('/tournaments/{id}', [TournamentController::class, 'update']);
    Route::delete('/tournaments/{id}', [TournamentController::class, 'destroy']);


    // Sports Module
    Route::get('/sports', [SportController::class, 'index']);
    Route::post('/sports', [SportController::class, 'store']);
    Route::put('/sports/{id}', [SportController::class, 'update']);
    Route::delete('/sports/{id}', [SportController::class, 'destroy']);
    Route::get('/sports/{id}/tournaments', [SportController::class, 'tournaments']);


    // Items Module
    Route::get('/items', [ItemController::class, 'index']);
    Route::post('/items', [ItemController::class, 'store']);
    Route::put('/items/{id}', [ItemController::class, 'update']);
    Route::delete('/items/{id}', [ItemController::class, 'destroy']);


    // Bundle Module
    Route::get('/bundles', [BundleController::class, 'index']);
    Route::post('/bundles', [BundleController::class, 'store']);
    Route::put('/bundles/{id}', [BundleController::class, 'update']);
    Route::delete('/bundles/{id}', [BundleController::class, 'destroy']);


    // Rental Module
    Route::get('/rentals', [RentalController::class, 'index']);
    Route::get('/rentals/user', [RentalController::class, 'userRentals']);
    Route::post('/rentals', [RentalController::class, 'store']);
    Route::put('/rentals/{id}', [RentalController::class, 'update']);
    Route::delete('/rentals/{id}', [RentalController::class, 'destroy']);


    // Rental Module
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle']);
});


// Sign in with Google
Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);
Route::post('/auth/google/login', [AuthController::class, 'googleLogin']);

// Sign in with Apple 
Route::get('/auth/apple/redirect', [AuthController::class, 'appleRedirect']);
Route::get('/auth/apple/callback', [AuthController::class, 'appleCallback']);
Route::post('/auth/apple/login', [AuthController::class, 'appleLogin']);
