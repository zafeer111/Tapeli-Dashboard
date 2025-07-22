<?php

use App\Enums\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BundleController;
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
    Route::post('/tournaments', [TournamentController::class, 'store'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);
    Route::put('/tournaments/{id}', [TournamentController::class, 'update'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);
    Route::delete('/tournaments/{id}', [TournamentController::class, 'destroy'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);


    // Sports Module
    Route::get('/sports', [SportController::class, 'index']);
    Route::post('/sports', [SportController::class, 'store'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);
    Route::put('/sports/{id}', [SportController::class, 'update'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);
    Route::delete('/sports/{id}', [SportController::class, 'destroy'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);
    Route::get('/sports/{id}/tournaments', [SportController::class, 'tournaments']);


    // Items Module
    Route::get('/items', [ItemController::class, 'index']);
    Route::post('/items', [ItemController::class, 'store'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);
    Route::put('/items/{id}', [ItemController::class, 'update'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);


    // Bundle Module
    Route::get('/bundles', [BundleController::class, 'index']);
    Route::post('/bundles', [BundleController::class, 'store'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);
    Route::put('/bundles/{id}', [BundleController::class, 'update'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);
    Route::delete('/bundles/{id}', [BundleController::class, 'destroy'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);


    // Rental Module
    Route::get('/rentals', [RentalController::class, 'index']);
    Route::post('/rentals', [RentalController::class, 'store'])->middleware('can:' . Permission::USER->value);
    Route::put('/rentals/{id}', [RentalController::class, 'update'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);
    Route::delete('/rentals/{id}', [RentalController::class, 'destroy'])->middleware('can:' . Permission::SUPER_ADMIN->value . ',' . Permission::MANAGER->value);
});


// Sign in with Google
Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect']);
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);
Route::post('/auth/google/login', [AuthController::class, 'googleLogin']);

// Sign in with Apple 
Route::get('/auth/apple/redirect', [AuthController::class, 'appleRedirect']);
Route::get('/auth/apple/callback', [AuthController::class, 'appleCallback']);
Route::post('/auth/apple/login', [AuthController::class, 'appleLogin']);
