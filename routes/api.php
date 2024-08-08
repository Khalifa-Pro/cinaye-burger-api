<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//Route::apiResource('burgers',\App\Http\Controllers\BurgerController::class);
/*Route::middleware('auth:sanctum')->group(function (){
    Route::middleware(['role:user'])->group(function () {
        Route::apiResource('burgers',\App\Http\Controllers\BurgerController::class);

    });
});*/
/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
/***
 * AUTH AUTHORIZ
 */

    /***
     * BURGERS CRUD API
     */
Route::get('index',[\App\Http\Controllers\BurgerController::class,'index']);
Route::get('show/{id}',[\App\Http\Controllers\BurgerController::class,'show']);
Route::post('create',[\App\Http\Controllers\BurgerController::class,'store']);
Route::get('recettes-journalieres',[\App\Http\Controllers\BurgerController::class,'recettesJournalieres']);
Route::put('update/{id}',[\App\Http\Controllers\BurgerController::class,'update']);
Route::post('delete/{id}',[\App\Http\Controllers\BurgerController::class,'delete']);
Route::post('search',[\App\Http\Controllers\BurgerController::class,'search']);
Route::post('archiver/{id}',[\App\Http\Controllers\BurgerController::class,'archiver']);
Route::post('desarchiver/{id}',[\App\Http\Controllers\BurgerController::class,'desarchiver']);
    /***
     * COMMANDES
     */
Route::get('commandes',[\App\Http\Controllers\LigneCommandeController::class,'index']);
Route::get('commandes-en-cours',[\App\Http\Controllers\LigneCommandeController::class,'commandesEnCours']);
Route::get('commandes-validees',[\App\Http\Controllers\LigneCommandeController::class,'commandesValidees']);
Route::get('commandes-annulees',[\App\Http\Controllers\LigneCommandeController::class,'commandesAnnulees']);
Route::post('update',[\App\Http\Controllers\LigneCommandeController::class,'update']);
Route::post('delete',[\App\Http\Controllers\LigneCommandeController::class,'delete']);
Route::post('archiver-commande/{id}',[\App\Http\Controllers\LigneCommandeController::class,'archiver']);
Route::post('valider/{id}',[\App\Http\Controllers\LigneCommandeController::class,'valider']);
Route::post('annuler/{id}',[\App\Http\Controllers\LigneCommandeController::class,'annuler']);
Route::get('nombre-commandes-en-cours',[\App\Http\Controllers\LigneCommandeController::class,'nbCommandesEnCours']);
Route::get('nombre-commandes-validees',[\App\Http\Controllers\LigneCommandeController::class,'nbCommandesValidees']);
Route::get('nombre-commandes-annulees',[\App\Http\Controllers\LigneCommandeController::class,'nbCommandesAnnulees']);

    /***
     * PAIEMENT
     */
Route::post('payer/{id}/{montant}',[\App\Http\Controllers\PaiementController::class,'payer']);
Route::get('paiements',[\App\Http\Controllers\PaiementController::class,'paiements']);
Route::get('bilan-journalier',[\App\Http\Controllers\PaiementController::class,'bilan']);
/***
 * AUTHENTIFICATION API
 */
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');

/***
 * COMMANDES CLIENTS
 */
Route::post('commander/{idBurger}',[\App\Http\Controllers\LigneCommandeController::class,'store']);
