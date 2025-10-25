<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\TrajetController;
use App\Http\Controllers\ReservationController;


Route::get('/', function () {
    return view('front-page');
});

Route::get('/about', function () {
    return view('about');
});

// // Routes pour les pages manquantes
// Route::get('/rechercher', function () {
//     return view('rechercher');
// });

Route::get('/publier', function () {
    return view('publier');
});
// Route::get('/publier', function () {
//     return view('publier');
// });

Route::get('/mes-reservations', function () {
    return view('mes-reservations');
});

Route::get('/tarifs', function () {
    return view('tarifs');
});

Route::get('/faq', function () {
    return view('faq');
});

Route::get('/contact', function () {
    return view('contact');
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


Route::post('/payer-panier/{conducteurId}/{idUtilisateur}/{paiementId}', [CartController::class, 'payerPanier'])->name('payer.panier');
Route::post('/update-places', [CartController::class, 'updatePlaces'])->name('update.places');
Route::post('/remove-from-cart', [CartController::class, 'removeFromCart'])->name('remove.from.cart');
Route::get('/profil', [ProfilController::class, 'index']);
Route::get('/edit-profil', [ProfilController::class, 'edit'])->name('profil.edit');
Route::post('/edit-profil', [ProfilController::class, 'update'])->name('profil.update');

Route::get('/trajets/search', [TrajetController::class, 'search']);
Route::post('/reservations', [TrajetController::class, 'reserve'])->name('reservations.store');
Route::get('/publier', [TrajetController::class, 'create'])->name('trajets.create');
Route::post('/publier', [TrajetController::class, 'store'])->name('trajets.store');
Route::get('/rechercher', [TrajetController::class, 'index'])->name('trajets.index');


Route::get('/trajets/search', [TrajetController::class, 'search']);
Route::get('/mes-reservations', [ReservationController::class, 'index'])->name('mes-reservations.index');
Route::put('/mes-reservations/{id}/update', [ReservationController::class, 'update'])->name('mes-reservations.update');
Route::delete('/mes-reservations/{id}', [ReservationController::class, 'destroy'])->name('mes-reservations.destroy');

Route::get('/trajets/{id}/availability', function($id) {
    $trajet = DB::table('Trajets')->where('IdTrajet', $id)->select('PlacesDisponibles')->first();
    if (!$trajet) {
        return response()->json(['error' => 'Trajet introuvable'], 404);
    }
    return response()->json(['places_disponibles' => (int)$trajet->PlacesDisponibles]);
});
