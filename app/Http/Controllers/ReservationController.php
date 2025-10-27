<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\TrajetConfirmeMail;
use App\Mail\ReservationAnnuleeParPassagerMail;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Afficher les réservations de l'utilisateur connecté
     */
    public function index()
    {
        $utilisateurId = session('utilisateur_id');
        $role = session('utilisateur_role');

        if (!$utilisateurId) {
            return redirect('/connexion')->with('error', 'Veuillez vous connecter pour voir vos réservations.');
        }

        // Supprimer les réservations invalides
        DB::table('Reservations')
            ->whereNull('IdPassager')
            ->orWhere('PlacesReservees', '<=', 0)
            ->delete();

        if (strtolower($role) === 'conducteur') {
            // Réservations pour les trajets où l'utilisateur est conducteur
            $reservations = DB::table('Trajets as t')
                ->join('Reservations as r', 't.IdTrajet', '=', 'r.IdTrajet')
                ->leftJoin('Utilisateurs as u', 'r.IdPassager', '=', 'u.IdUtilisateur')
                ->where('t.IdConducteur', $utilisateurId)
                ->select(
                    't.IdTrajet',
                    't.Depart',
                    't.Destination',
                    't.DateTrajet',
                    't.HeureTrajet',
                    't.Prix',
                    DB::raw('SUM(r.PlacesReservees) as PlacesReservees'),
                    DB::raw('GROUP_CONCAT(DISTINCT CONCAT(u.Prenom, " ", u.Nom) SEPARATOR ", ") as NomsPassagers')
                )
                ->groupBy(
                    't.IdTrajet',
                    't.Depart',
                    't.Destination',
                    't.DateTrajet',
                    't.HeureTrajet',
                    't.Prix'
                )
                ->havingRaw('SUM(r.PlacesReservees) > 0')
                ->get();
        } else {
            // Réservations du passager connecté
            $reservations = DB::table('Reservations as r')
                ->join('Trajets as t', 'r.IdTrajet', '=', 't.IdTrajet')
                ->join('Utilisateurs as u', 't.IdConducteur', '=', 'u.IdUtilisateur')
                ->where('r.IdPassager', $utilisateurId)
                ->select(
                    'r.IdReservation',
                    'r.PlacesReservees',
                    't.IdTrajet',
                    't.Depart',
                    't.Destination',
                    't.DateTrajet',
                    't.HeureTrajet',
                    't.Prix',
                    'u.IdUtilisateur as IdConducteur',
                    'u.Nom as NomConducteur',
                    'u.Prenom as PrenomConducteur'
                )
                ->get();
        }

        return view('mes-reservations', compact('reservations'));
    }

    /**
     * Mettre à jour une réservation existante (nombre de places)
     */
    public function update(Request $request, $id)
    {
        $userId = session('utilisateur_id');
        if (!$userId) return redirect('/connexion');

        $request->validate([
            'PlacesReservees' => 'required|integer|min:1'
        ]);

        $reservation = DB::table('Reservations')->where('IdReservation', $id)->first();
        if (!$reservation || $reservation->IdPassager != $userId) {
            return redirect('/mes-reservations')->with('error', 'Réservation introuvable.');
        }

        $trajet = DB::table('Trajets')->where('IdTrajet', $reservation->IdTrajet)->first();
        if (!$trajet) {
            return redirect('/mes-reservations')->with('error', 'Trajet introuvable.');
        }

        $diff = $request->PlacesReservees - $reservation->PlacesReservees;

        if ($diff > $trajet->PlacesDisponibles) {
            return redirect('/mes-reservations')->with('error', 'Pas assez de places disponibles.');
        }

        // Mise à jour de la réservation et des places disponibles
        DB::table('Reservations')->where('IdReservation', $id)
            ->update(['PlacesReservees' => $request->PlacesReservees]);

        if ($diff > 0) {
            DB::table('Trajets')->where('IdTrajet', $reservation->IdTrajet)->decrement('PlacesDisponibles', $diff);
        } elseif ($diff < 0) {
            DB::table('Trajets')->where('IdTrajet', $reservation->IdTrajet)->increment('PlacesDisponibles', abs($diff));
        }

        // Envoi d'email de confirmation
        try {
            $passager = DB::table('Utilisateurs')->where('IdUtilisateur', $userId)->first();
            if ($passager) {
                $updatedReservation = DB::table('Reservations')->where('IdReservation', $id)->first();
                Mail::to($passager->Courriel)->send(
                    new TrajetConfirmeMail($trajet, $passager, $updatedReservation, 'modified')
                );
            }
        } catch (\Exception $e) {
            Log::error('Erreur email modification réservation : ' . $e->getMessage());
        }

        return redirect('/mes-reservations')->with('success', 'Réservation mise à jour.');
    }

    /**
     * Annuler une réservation
     */
    public function destroy($id)
    {
        $userId = session('utilisateur_id');
        if (!$userId) return redirect('/connexion');

        $reservation = DB::table('Reservations')->where('IdReservation', $id)->first();
        if (!$reservation || $reservation->IdPassager != $userId) {
            return redirect('/mes-reservations')->with('error', 'Réservation introuvable.');
        }

        $trajet = DB::table('Trajets')->where('IdTrajet', $reservation->IdTrajet)->first();
        $passager = DB::table('Utilisateurs')->where('IdUtilisateur', $userId)->first();

        // Réincrémenter les places disponibles
        DB::table('Trajets')->where('IdTrajet', $reservation->IdTrajet)
            ->increment('PlacesDisponibles', $reservation->PlacesReservees);

        // Marquer le paiement comme annulé
        DB::table('Paiements')
            ->where('IdTrajet', $reservation->IdTrajet)
            ->where('IdUtilisateur', $userId)
            ->update(['Statut' => 'Annulé']);

        // Envoi d'email d'annulation
        try {
            if ($passager && $trajet) {
                Mail::to($passager->Courriel)->send(
                    new ReservationAnnuleeParPassagerMail($trajet, $passager, $reservation)
                );
            }
        } catch (\Exception $e) {
            Log::error('Erreur email annulation réservation : ' . $e->getMessage());
        }

        // Supprimer la réservation
        DB::table('Reservations')->where('IdReservation', $id)->delete();

        return redirect('/mes-reservations')->with('success', 'Réservation annulée.');
    }
    public function store(Request $request)
    {
        // obtenir l'utilisateur (Auth ou session)
        $userId = Auth::id() ?: session('utilisateur_id');
        if (! $userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'IdTrajet' => 'required|integer|exists:Trajets,IdTrajet',
            'PlacesReservees' => 'required|integer|min:1'
        ]);

        $idTrajet = (int) $request->input('IdTrajet');
        $places = (int) $request->input('PlacesReservees');

        try {
            $paiementId = DB::transaction(function () use ($userId, $idTrajet, $places) {
                // récupérer le trajet pour calcul du montant (lecture simple)
                $trajet = DB::table('Trajets')->where('IdTrajet', $idTrajet)->first();
                if (! $trajet) {
                    throw new \Exception('Trajet introuvable');
                }

                $prixUnitaire = isset($trajet->Prix) ? floatval($trajet->Prix) : 0.0;
                $montant = round($prixUnitaire * $places, 2);

                // insérer un paiement — aucune modification des Reservations ou Trajets
                return DB::table('Paiements')->insertGetId([
                    'IdUtilisateur' => $userId,
                    'IdTrajet' => $idTrajet,
                    'NombrePlaces' => $places,
                    'Montant' => $montant,
                    'Statut' => 'En attente',
                    'MethodePaiement' => 'Carte Crédit',
                    'DateCreation' => Carbon::now()
                ], 'IdPaiement');
            });

            return response()->json(['success' => true, 'IdPaiement' => $paiementId], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
