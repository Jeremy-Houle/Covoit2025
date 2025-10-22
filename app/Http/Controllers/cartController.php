<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // public function payerPanier($p_conducteurId, $p_idUtilisateur, $p_Idpaiement)
    // {
    //     try {
            
    //         Log::info('Tentative de paiement', [
    //             'conducteur_id' => $p_conducteurId,
    //             'utilisateur_id' => $p_idUtilisateur,
    //             'paiement_id' => $p_Idpaiement
    //         ]);

           
    //         $paiement = DB::table('Paiements')
    //             ->where('IdPaiement', $p_Idpaiement)
    //             ->where('IdUtilisateur', $p_idUtilisateur)
    //             ->first();
            
    //         if (!$paiement) {
    //             throw new \Exception('Paiement introuvable');
    //         }
            
    //         $utilisateur = DB::table('Utilisateurs')
    //             ->where('IdUtilisateur', $p_idUtilisateur)
    //             ->first();
            
    //         $soldeActuel = $utilisateur->Solde ?? 0;
    //         $montantPaiement = $paiement->Montant;
            
    //         if ($soldeActuel < $montantPaiement) {
    //             throw new \Exception("Fonds insuffisants. Solde disponible: {$soldeActuel}$, Montant requis: {$montantPaiement}$");
    //         }
            
           
    //         DB::statement("CALL PayerPanier(?, ?, ?)", [$p_conducteurId, $p_idUtilisateur, $p_Idpaiement]);
            
    //         Log::info('Paiement effectué avec succès', [
    //             'conducteur_id' => $p_conducteurId,
    //             'utilisateur_id' => $p_idUtilisateur,
    //             'paiement_id' => $p_Idpaiement
    //         ]);

    //         return redirect()->route('cart')->with('payment_success', 'Paiement effectué avec succès !');
            
    //     } catch (\Exception $e) {
    //         Log::error('Erreur lors du paiement', [
    //             'error' => $e->getMessage(),
    //             'conducteur_id' => $p_conducteurId,
    //             'utilisateur_id' => $p_idUtilisateur,
    //             'paiement_id' => $p_Idpaiement
    //         ]);

    //         return redirect()->route('cart')->with('error', 'Erreur lors du paiement: ' . $e->getMessage());
    //     }
    // }
    public function payerPanier($p_conducteurId, $p_idUtilisateur, $p_Idpaiement)
{
    try {
        Log::info('Tentative de paiement', [
            'conducteur_id' => $p_conducteurId,
            'utilisateur_id' => $p_idUtilisateur,
            'paiement_id' => $p_Idpaiement
        ]);
        
        $paiement = DB::table('Paiements')
            ->where('IdPaiement', $p_Idpaiement)
            ->where('IdUtilisateur', $p_idUtilisateur)
            ->first();

        if (!$paiement) {
            throw new \Exception("Paiement introuvable (#{$p_Idpaiement})");
        }

        $trajet = DB::table('Trajets')
            ->where('IdTrajet', $paiement->IdTrajet)
            ->first();

        if (!$trajet) {
            throw new \Exception("Trajet introuvable pour le paiement #{$p_Idpaiement}");
        }

        $utilisateur = DB::table('Utilisateurs')
            ->where('IdUtilisateur', $p_idUtilisateur)
            ->first();

        if (!$utilisateur) {
            throw new \Exception("Utilisateur introuvable (#{$p_idUtilisateur})");
        }

        $soldeActuel = $utilisateur->Solde ?? 0;

        $montantAttendu = $trajet->Prix * $paiement->NombrePlaces;

        if ($soldeActuel < $montantAttendu) {
            throw new \Exception("Fonds insuffisants. Solde actuel: {$soldeActuel}$, requis: {$montantAttendu}$");
        }

        DB::statement("CALL PayerPanier(?, ?, ?)", [
            $p_conducteurId,
            $p_idUtilisateur,
            $p_Idpaiement
        ]);

        Log::info('Paiement effectué avec succès', [
            'conducteur_id' => $p_conducteurId,
            'utilisateur_id' => $p_idUtilisateur,
            'paiement_id' => $p_Idpaiement,
            'montant' => $montantAttendu
        ]);
        
        return redirect()->route('cart')->with('payment_success', "Paiement de {$montantAttendu}$ effectué avec succès !");
        
    } catch (\Exception $e) {
        Log::error('Erreur lors du paiement', [
            'error' => $e->getMessage(),
            'conducteur_id' => $p_conducteurId,
            'utilisateur_id' => $p_idUtilisateur,
            'paiement_id' => $p_Idpaiement
        ]);

        return redirect()->route('cart')->with('error', 'Erreur lors du paiement : ' . $e->getMessage());
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

    public function updatePlaces(Request $request)
    {
        try {
            $paiementId = $request->input('paiement_id');
            $newPlaces = $request->input('places');
            $userId = session('utilisateur_id', 1);

           
            if (!$paiementId || !$newPlaces || $newPlaces < 1) {
                return response()->json(['success' => false, 'message' => 'Données invalides']);
            }

            
            $paiement = DB::table('Paiements')
                ->where('IdPaiement', $paiementId)
                ->where('IdUtilisateur', $userId)
                ->first();

            if (!$paiement) {
                return response()->json(['success' => false, 'message' => 'Paiement introuvable']);
            }

            DB::statement("CALL ModifierNombrePlaces(?, ?)", [$paiementId, $newPlaces]);

            $updatedPaiement = DB::table('Paiements')
                ->where('IdPaiement', $paiementId)
                ->first();

            return response()->json([
                'success' => true,
                'places' => (int)$updatedPaiement->NombrePlaces,
                'new_amount' => (float)$updatedPaiement->Montant,
                'price_per_place' => (float)($updatedPaiement->Montant / $updatedPaiement->NombrePlaces)
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des places', [
                'paiement_id' => $request->input('paiement_id'),
                'places' => $request->input('places'),
                'error' => $e->getMessage()
            ]);

            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    }
}