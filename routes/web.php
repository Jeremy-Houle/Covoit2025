<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\TrajetController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\LesMessageController;
use App\Http\Controllers\MotDePasseController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CommentsController;



Route::get('/', function () {
    return view('front-page');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/publier', function () {
    return view('publier');
});

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
    $userId = session('utilisateur_id');
    if (!$userId) {
        return redirect('/connexion')->with('error', 'Veuillez vous connecter pour consulter votre panier.');
    }
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

Route::get('/forgot-password', [MotDePasseController::class, 'showRequestForm'])->name('password.request');
Route::post('/forgot-password', [MotDePasseController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password', [MotDePasseController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [MotDePasseController::class, 'resetPassword'])->name('password.update');


Route::post('/payer-panier/{conducteurId}/{idUtilisateur}/{paiementId}', [CartController::class, 'payerPanier'])->name('payer.panier');
Route::post('/update-places', [CartController::class, 'updatePlaces'])->name('update.places');
Route::post('/remove-from-cart', [CartController::class, 'removeFromCart'])->name('remove.from.cart');
Route::get('/historique-transactions', [CartController::class, 'historique'])->name('historique.transactions');
Route::get('/profil', [ProfilController::class, 'index']);
Route::get('/edit-profil', [ProfilController::class, 'edit'])->name('profil.edit');
Route::post('/edit-profil', [ProfilController::class, 'update'])->name('profil.update');

Route::get('/trajets/search', [TrajetController::class, 'search']);
Route::post('/reservations', [ReservationController::class, 'store'])->middleware('web');
Route::get('/publier', [TrajetController::class, 'create'])->name('trajets.create');
Route::post('/publier', [TrajetController::class, 'store'])->name('trajets.store');
Route::get('/rechercher', [TrajetController::class, 'index'])->name('trajets.index');
Route::put('/trajets/{id}/update', [TrajetController::class, 'updateTrajet'])->name('trajets.update');
Route::delete('/trajets/{id}/cancel', [TrajetController::class, 'cancelTrajet'])->name('trajets.cancel');
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

Route::get('/api/reservations', [ReservationController::class, 'myReservations'])->middleware('auth');

// Routes pour les favoris
Route::get('/api/favoris', [FavoriController::class, 'index'])->name('favoris.index');
Route::post('/api/favoris/toggle', [FavoriController::class, 'toggle'])->name('favoris.toggle');
Route::get('/api/favoris/check/{idTrajet}', [FavoriController::class, 'check'])->name('favoris.check');

Route::get('/messages/{id}', [LesMessageController::class, 'show'])->name('messages.show');
Route::post('/messages', [LesMessageController::class, 'store'])->name('messages.store');
Route::get('/messages', [LesMessageController::class, 'index'])->name('messages.index');
Route::get('/messages/{id}', [LesMessageController::class, 'show'])->name('messages.show');

Route::get('/message', [LesMessageController::class, 'index'])->name('message.index');
Route::get('/message/{id}', [LesMessageController::class, 'show'])->name('message.show');
Route::post('/message/{id}', [LesMessageController::class, 'store'])->name('message.send');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/trajet/{trajet}/commenter', [CommentsController::class, 'create'])
    ->name('comments.create');
Route::post('/comments/store', [CommentsController::class, 'store'])->name('comments.store');

