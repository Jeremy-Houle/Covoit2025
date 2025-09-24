<?php

use App\Http\Controllers\cartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PanierController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/cart', function () {
    return view('cart');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/test', function () {
    return view('test');
});

Route::get('/Panier', function () {
    $paiements = DB::table('Paiements as p')
        ->join('Trajets as t', 'p.IdTrajet', '=', 't.IdTrajet')
    ->join('Vehicules as v', 't.IdConducteur', '=', 'v.IdConducteur')
    ->join('Utilisateurs as u', 't.IdConducteur', '=', 'u.IdUtilisateur')
    ->select('p.*', 't.*', 'v.*')
    // ->where('p.IdUtilisateur', 1) //auth()->id())
    ->get();
    return view('panier', ['paiements' => $paiements]);
})->name('Panier');


Route::post('/payer/{conducteurId}/{utilisateurId}', [CartController::class, 'payerPanier'])
    ->name('payer.panier');
