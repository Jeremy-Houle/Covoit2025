<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmationPaiementMail;

class CartController extends Controller
{
    
    public function payerPanier(Request $request, $p_conducteurId, $p_idUtilisateur, $p_Idpaiement)
{
    try {
        $paymentType = $request->input('payment_type', 'solde'); 
        $paypalOrderId = $request->input('paypal_order_id');
        $paypalStatus = $request->input('paypal_status');
        
        Log::info('Tentative de paiement', [
            'conducteur_id' => $p_conducteurId,
            'utilisateur_id' => $p_idUtilisateur,
            'paiement_id' => $p_Idpaiement,
            'payment_type' => $paymentType,
            'paypal_order_id' => $paypalOrderId
        ]);
        
        $paiement = DB::table('paiements')
            ->where('IdPaiement', $p_Idpaiement)
            ->where('IdUtilisateur', $p_idUtilisateur)
            ->first();

        if (!$paiement) {
            throw new \Exception("Paiement introuvable (#{$p_Idpaiement})");
        }

        $trajet = DB::table('trajets')
            ->where('IdTrajet', $paiement->IdTrajet)
            ->first();

        if (!$trajet) {
            throw new \Exception("Trajet introuvable pour le paiement #{$p_Idpaiement}");
        }

        $utilisateur = DB::table('utilisateurs')
            ->where('IdUtilisateur', $p_idUtilisateur)
            ->first();

        if (!$utilisateur) {
            throw new \Exception("Utilisateur introuvable (#{$p_idUtilisateur})");
        }

        $montantAttendu = $trajet->Prix * $paiement->NombrePlaces;

        if ($paymentType === 'paypal') {
            if (!$paypalOrderId || !$paypalStatus || $paypalStatus !== 'COMPLETED') {
                throw new \Exception("Paiement PayPal non confirmé");
            }
            
            Log::info('Paiement PayPal confirmé', [
                'order_id' => $paypalOrderId,
                'status' => $paypalStatus,
                'montant' => $montantAttendu
            ]);
            
        } else {
            $soldeActuel = $utilisateur->Solde ?? 0;
            
            if ($soldeActuel < $montantAttendu) {
                throw new \Exception("Fonds insuffisants. Solde actuel: {$soldeActuel}$, requis: {$montantAttendu}$");
            }
            
            Log::info('Paiement avec solde', [
                'solde_actuel' => $soldeActuel,
                'montant' => $montantAttendu
            ]);
        }

        DB::statement("CALL PayerPanier(?, ?, ?, ?)", [
            $p_conducteurId,
            $p_idUtilisateur,
            $p_Idpaiement,
            $paymentType
        ]);

        try {
            $conducteur = DB::table('utilisateurs')
                ->where('IdUtilisateur', $p_conducteurId)
                ->first();
            
            DB::table('historiquetransactions')->insert([
                'IdUtilisateur' => $p_idUtilisateur,
                'IdTrajet' => $trajet->IdTrajet,
                'IdConducteur' => $p_conducteurId,
                'IdPaiement' => $p_Idpaiement,
                'IdReservation' => null, 
                'NombrePlaces' => $paiement->NombrePlaces,
                'Montant' => $montantAttendu,
                'Statut' => 'Payé',
                'MethodePaiement' => $paymentType === 'paypal' ? 'PayPal' : 'Solde',
                'Depart' => $trajet->Depart,
                'Destination' => $trajet->Destination,
                'DateTrajet' => $trajet->DateTrajet,
                'HeureTrajet' => $trajet->HeureTrajet,
                'PrixUnitaire' => $trajet->Prix,
                'NomConducteur' => $conducteur->Nom ?? '',
                'PrenomConducteur' => $conducteur->Prenom ?? '',
                'DateTransaction' => now(),
                'CreatedAt' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'insertion dans l\'historique: ' . $e->getMessage());
        }

        Log::info('Paiement effectué avec succès', [
            'conducteur_id' => $p_conducteurId,
            'utilisateur_id' => $p_idUtilisateur,
            'paiement_id' => $p_Idpaiement,
            'montant' => $montantAttendu,
            'payment_type' => $paymentType
        ]);

        try {
            Mail::to($utilisateur->Courriel)->send(
                new ConfirmationPaiementMail($paiement, $trajet, $utilisateur)
            );
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de confirmation: ' . $e->getMessage());
        }
        
        $message = $paymentType === 'paypal' 
            ? "Paiement PayPal de " . number_format($montantAttendu, 2) . "$ effectué avec succès !"
            : "Paiement de " . number_format($montantAttendu, 2) . "$ effectué avec succès avec votre solde !";
            
        return redirect()->route('cart')->with('payment_success', $message);
        
    } catch (\Exception $e) {
        Log::error('Erreur lors du paiement', [
            'error' => $e->getMessage(),
            'conducteur_id' => $p_conducteurId,
            'utilisateur_id' => $p_idUtilisateur,
            'paiement_id' => $p_Idpaiement,
            'payment_type' => $request->input('payment_type', 'solde')
        ]);

        if (strpos($e->getMessage(), 'Fonds insuffisants') !== false) {
            return redirect()->route('cart')->with('error', 'Fonds insuffisants');
        }
        
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

            $paiement = DB::table('paiements')
                ->where('IdPaiement', $paiementId)
                ->where('IdUtilisateur', $userId)
                ->first();

            if (!$paiement) {
                return response()->json(['success' => false, 'message' => 'Paiement introuvable']);
            }

            $trajet = DB::table('trajets')
                ->where('IdTrajet', $paiement->IdTrajet)
                ->first();

            if (!$trajet) {
                return response()->json(['success' => false, 'message' => 'Trajet introuvable']);
            }

            $placesDisponibles = (int)$trajet->PlacesDisponibles;
            $placesActuelles = (int)$paiement->NombrePlaces;
            $difference = $newPlaces - $placesActuelles;

            if ($difference >= $placesDisponibles) {
                return response()->json([
                    'success' => false, 
                    'max_places' => $placesActuelles + $placesDisponibles
                ]);
            }

            try {
                DB::statement("CALL ModifierNombrePlaces(?, ?)", [$paiementId, $newPlaces]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false, 
                    'message' => $e->getMessage()
                ]);
            }

            $updatedPaiement = DB::table('paiements')
                ->where('IdPaiement', $paiementId)
                ->first();

            $updatedTrajet = DB::table('trajets')
                ->where('IdTrajet', $paiement->IdTrajet)
                ->first();

            $prixParPersonne = (float)($paiement->Montant / $paiement->NombrePlaces);
            $nouveauMontantTotal = $prixParPersonne * $updatedPaiement->NombrePlaces;
            
            return response()->json([
                'success' => true,
                'places' => (int)$updatedPaiement->NombrePlaces,
                'new_amount' => $nouveauMontantTotal,
                'price_per_place' => $prixParPersonne,
                'places_disponibles' => (int)$updatedTrajet->PlacesDisponibles
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

    public function removeFromCart(Request $request)
    {
        try {
            $paiementId = $request->input('paiement_id');
            $userId = session('utilisateur_id', 1);

            if (!$paiementId) {
                return response()->json(['success' => false, 'message' => 'ID de paiement manquant']);
            }

            $paiement = DB::table('paiements')
                ->where('IdPaiement', $paiementId)
                ->where('IdUtilisateur', $userId)
                ->first();

            if (!$paiement) {
                return response()->json(['success' => false, 'message' => 'Paiement introuvable']);
            }

            DB::table('paiements')
                ->where('IdPaiement', $paiementId)
                ->where('IdUtilisateur', $userId)
                ->delete();

            Log::info('Paiement supprimé du panier', [
                'paiement_id' => $paiementId,
                'utilisateur_id' => $userId
            ]);

            return redirect()->route('cart')->with('success', 'Trajet supprimé du panier');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du paiement', [
                'paiement_id' => $request->input('paiement_id'),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('cart')->with('error', 'Erreur lors de la suppression');
        }
    }

    public function historique()
    {
        $userId = session('utilisateur_id');
        $role = session('utilisateur_role');
        
        if (!$userId) {
            return redirect('/connexion')->with('error', 'Veuillez vous connecter pour consulter votre historique de transactions.');
        }

        if (strtolower($role) === 'conducteur') {
            $transactions = DB::table('historiquetransactions')
                ->where('IdConducteur', $userId)
                ->join('Utilisateurs', 'HistoriqueTransactions.IdUtilisateur', '=', 'Utilisateurs.IdUtilisateur')
                ->select(
                    'HistoriqueTransactions.*',
                    'Utilisateurs.Nom as NomPassager',
                    'Utilisateurs.Prenom as PrenomPassager'
                )
                ->orderBy('DateTransaction', 'desc')
                ->get();
        } else {
            $transactions = DB::table('historiquetransactions')
                ->where('IdUtilisateur', $userId)
                ->orderBy('DateTransaction', 'desc')
                ->get();
        }

        return view('historique-transactions', [
            'transactions' => $transactions,
            'role' => $role
        ]);
    }
}