<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function myReservations(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // exemple : récupérer réservations avec trajet lié
        $reservations = $user->reservations()->with('trajet')->get();

        return response()->json($reservations);
    }

    /*
    public function index()
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

       // Afficher toutes les réservations
        $reservations = DB::table('Reservations as r')
            ->join('Trajets as t', 'r.IdTrajet', '=', 't.IdTrajet')
            ->join('Utilisateurs as u', 't.IdConducteur', '=', 'u.IdUtilisateur')
            ->leftJoin('Paiements as p', function ($join) use ($userId) {
                $join->on('p.IdTrajet', '=', 't.IdTrajet')
                     ->where('p.IdUtilisateur', '=', $userId);
            })
            ->where('r.IdPassager', $userId)
            ->where('t.DateTrajet', '>=', Carbon::today())
            ->select(
                'r.IdReservation',
                't.Depart',
                't.Destination',
                'r.IdTrajet', 
                't.IdTrajet as TrajetId', 
                't.DateTrajet',
                't.HeureTrajet',
                'u.Prenom as PrenomConducteur',
                'u.Nom as NomConducteur',
                'r.PlacesReservees',
                't.PlacesDisponibles',
                't.Prix',
                'p.Statut as StatutPaiement',
                'p.MethodePaiement',
                'p.Montant'
            )
            ->orderBy('t.DateTrajet', 'asc')
            ->get();

        return view('mes-reservations', compact('reservations'));
    }
    */
    public function index()
{
    $utilisateurId = session('utilisateur_id');
    $role = session('utilisateur_role') ?? 'Passager';

    if (!$utilisateurId) {
        return redirect('/connexion')->with('error', 'Veuillez vous connecter pour voir vos réservations.');
    }

    if (strtolower($role) === 'conducteur') {
        // Réservations reçues pour les trajets où l'utilisateur est conducteur
        $reservations = DB::table('Reservations as r')
            ->join('Trajets as t', 'r.IdTrajet', '=', 't.IdTrajet')
            ->join('Utilisateurs as p', 'r.IdPassager', '=', 'p.IdUtilisateur')
            ->where('t.IdConducteur', $utilisateurId)
            ->select(
                'r.*',
                't.IdTrajet',
                't.Depart',
                't.Destination',
                't.DateTrajet',
                't.HeureTrajet',
                't.Prix',
                't.PlacesDisponibles',
                't.IdConducteur',
                'p.IdUtilisateur as IdPassager',
                'p.Nom as NomPassager',
                'p.Prenom as PrenomPassager'
            )
            ->orderBy('r.DateReservation', 'desc')
            ->get();
    } else {
        // Réservations du passager connecté ; joindre le trajet pour obtenir le conducteur
        $reservations = DB::table('Reservations as r')
            ->join('Trajets as t', 'r.IdTrajet', '=', 't.IdTrajet')
            ->join('Utilisateurs as c', 't.IdConducteur', '=', 'c.IdUtilisateur')
            ->where('r.IdPassager', $utilisateurId)
            ->select(
                'r.*',
                't.IdTrajet',
                't.Depart',
                't.Destination',
                't.DateTrajet',
                't.HeureTrajet',
                't.Prix',
                't.PlacesDisponibles',
                't.IdConducteur',
                'c.IdUtilisateur as IdConducteur',
                'c.Nom as NomConducteur',
                'c.Prenom as PrenomConducteur'
            )
            ->orderBy('r.DateReservation', 'desc')
            ->get();
    }

    return view('mes-reservations', compact('reservations'));
}

    //  Modifier une réservation (nombre de places)
public function update(Request $request, $id)
{
    $userId = session('utilisateur_id');
    if (!$userId) return redirect('/connexion');

    $request->validate([
        'PlacesReservees' => 'required|integer|min:1'
    ]);

    // Récupérer la réservation existante
    $reservation = DB::table('Reservations')->where('IdReservation', $id)->first();
    if (!$reservation || $reservation->IdPassager != $userId) {
        return redirect('/mes-reservations')->with('error', 'Réservation introuvable.');
    }

    // Récupérer le trajet lié
    $trajet = DB::table('Trajets')->where('IdTrajet', $reservation->IdTrajet)->first();
    if (!$trajet) {
        return redirect('/mes-reservations')->with('error', 'Trajet introuvable.');
    }

    $oldPlaces = $reservation->PlacesReservees;
    $newPlaces = $request->PlacesReservees;
    $diff = $newPlaces - $oldPlaces;

    // Vérifie qu'on ne dépasse pas les places disponibles
    if ($diff > $trajet->PlacesDisponibles) {
        return redirect('/mes-reservations')->with('error', 'Pas assez de places disponibles.');
    }

    // Met à jour la réservation
    DB::table('Reservations')->where('IdReservation', $id)->update([
        'PlacesReservees' => $newPlaces
    ]);

    // Ajuste les places disponibles du trajet
    if ($diff > 0) {
        // L'utilisateur ajoute des places → on en enlève du trajet
        DB::table('Trajets')->where('IdTrajet', $reservation->IdTrajet)
            ->decrement('PlacesDisponibles', $diff);
    } elseif ($diff < 0) {
        // L'utilisateur réduit ses places → on en remet dans le trajet
        DB::table('Trajets')->where('IdTrajet', $reservation->IdTrajet)
            ->increment('PlacesDisponibles', abs($diff));
    }

    return redirect('/mes-reservations')->with('success', 'Réservation mise à jour.');
}

    // Annuler une réservation
    public function destroy($id)
    {
        $userId = session('utilisateur_id');
        if (!$userId) return redirect('/connexion');

        $reservation = DB::table('Reservations')->where('IdReservation', $id)->first();
        if (!$reservation || $reservation->IdPassager != $userId) {
            return redirect('/mes-reservations')->with('error', 'Réservation introuvable.');
        }

        // Réaugmenter les places du trajet
        DB::table('Trajets')
            ->where('IdTrajet', $reservation->IdTrajet)
            ->increment('PlacesDisponibles', $reservation->PlacesReservees);

        // Mettre à jour le paiement
        DB::table('Paiements')
            ->where('IdTrajet', $reservation->IdTrajet)
            ->where('IdUtilisateur', $userId)
            ->update(['Statut' => 'Annulé']);

        
        DB::table('Reservations')->where('IdReservation', $id)->delete();

        return redirect('/mes-reservations')->with('success', 'Réservation annulée.');
    }
    // Créer uniquement une entrée de paiement (ne pas créer de réservation)
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
