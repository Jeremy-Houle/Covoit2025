<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('front-page');
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

