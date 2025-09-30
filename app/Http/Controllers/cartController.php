<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function payerPanier($p_conducteurId, $p_idUtilisateur, $p_Idpaiement)
    {
        try {
            // Log de débogage
            Log::info('Tentative de paiement', [
                'conducteur_id' => $p_conducteurId,
                'utilisateur_id' => $p_idUtilisateur,
                'paiement_id' => $p_Idpaiement
            ]);

            // Vérifier les fonds avant d'appeler la procédure
            $paiement = DB::table('Paiements')
                ->where('IdPaiement', $p_Idpaiement)
                ->where('IdUtilisateur', $p_idUtilisateur)
                ->first();
            
            if (!$paiement) {
                throw new \Exception('Paiement introuvable');
            }
            
            $utilisateur = DB::table('Utilisateurs')
                ->where('IdUtilisateur', $p_idUtilisateur)
                ->first();
            
            $soldeActuel = $utilisateur->Solde ?? 0;
            $montantPaiement = $paiement->Montant;
            
            if ($soldeActuel < $montantPaiement) {
                throw new \Exception("Fonds insuffisants. Solde disponible: {$soldeActuel}$, Montant requis: {$montantPaiement}$");
            }
            
            // Exécuter la procédure stockée
            DB::statement("CALL PayerPanier(?, ?, ?)", [$p_conducteurId, $p_idUtilisateur, $p_Idpaiement]);
            
            Log::info('Paiement effectué avec succès', [
                'conducteur_id' => $p_conducteurId,
                'utilisateur_id' => $p_idUtilisateur,
                'paiement_id' => $p_Idpaiement
            ]);

            return redirect()->route('cart')->with('payment_success', 'Paiement effectué avec succès !');
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du paiement', [
                'error' => $e->getMessage(),
                'conducteur_id' => $p_conducteurId,
                'utilisateur_id' => $p_idUtilisateur,
                'paiement_id' => $p_Idpaiement
            ]);

            return redirect()->route('cart')->with('error', 'Erreur lors du paiement: ' . $e->getMessage());
        }
    }

    public function getidConducteur($p_Idpaiement)
    {
        try {
            return DB::select("CALL GetConducteurByPaiement(?)", [$p_Idpaiement]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du conducteur', [
                'error' => $e->getMessage(),
                'paiement_id' => $p_Idpaiement
            ]);
            return [];
        }
    }
}