<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TontineController;
use App\Http\Controllers\CotisationController;
use App\Http\Controllers\AuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route pour créer une tontine, accessible uniquement aux utilisateurs authentifiés
Route::middleware('auth:sanctum')->post('/tontines', [TontineController::class, 'store']);

//Route pour récupérer les tontines de l'utilisateur authentifié
Route::middleware('auth:sanctum')->get('/mes-tontines', [TontineController::class, 'mesTontines']);


//Route pour récupérer les détails d'une tontine spécifique, accessible uniquement aux utilisateurs authentifiés
Route::middleware('auth:sanctum')->get('/tontines/{tontine}', [TontineController::class, 'show']);

//Routes API pour l'authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

//Route pour ajouter un membre à une tontine, accessible uniquement aux utilisateurs authentifiés
Route::middleware('auth:sanctum')->post('/tontines/{tontine}/membres', [TontineController::class, 'ajouterMembre']);

// Route pour marquer une cotisation comme payée.
Route::middleware('auth:sanctum')->post('/cotisations/{cotisation}/payer', [CotisationController::class, 'marquerPaye']);