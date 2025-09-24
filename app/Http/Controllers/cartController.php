<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function payerPanier($p_conducteurId, $p_idUtilisateur)
{
    DB::statement("CALL PayerPanier(?, ?)", [$p_conducteurId, $p_idUtilisateur]);
    return redirect()->route('Panier')->with('success', 'Paiement effectué!');
}
}