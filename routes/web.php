<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;

Route::get('/', function () {
    return view('front-page');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/about', function () {
    return view('about');
});



Route::get('/cart', function () {
    $paiements = DB::table('Paiements as p')
        ->join('Trajets as t', 'p.IdTrajet', '=', 't.IdTrajet')
        ->join('Utilisateurs as u', 't.IdConducteur', '=', 'u.IdUtilisateur')
        ->select('p.*', 't.*', 'u.Nom as ConducteurNom', 'u.Prenom as ConducteurPrenom')
        ->where('p.IdUtilisateur', session('utilisateur_id', 1))
        ->distinct()
        ->get();
    return view('panier', ['paiements' => $paiements]);
})->name('cart');

Route::get('/connexion', [AuthController::class, 'afficherConnexion']);
Route::post('/connexion', [AuthController::class, 'traiterConnexion']);

Route::get('/inscription', [AuthController::class, 'afficherInscription']);
Route::post('/inscription', [AuthController::class, 'traiterInscription']);

Route::get('/deconnexion', [AuthController::class, 'deconnexion']);


Route::post('/payer-panier/{conducteurId}/{idUtilisateur}', [CartController::class, 'payerPanier'])->name('payer.panier');
Route::get('/profil', [ProfilController::class, 'index']);
Route::get('/edit-profil', [ProfilController::class, 'edit'])->name('profil.edit');
Route::post('/edit-profil', [ProfilController::class, 'update'])->name('profil.update');


