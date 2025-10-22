<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationController extends Controller
{
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
    $role = session('utilisateur_role'); // 'conducteur' ou 'passager'

    if (!$utilisateurId) {
        return redirect('/connexion')->with('error', 'Veuillez vous connecter pour voir vos réservations.');
    }

    if ($role === 'Conducteur') {
        // 🚗 Si l'utilisateur est conducteur : voir les réservations reçues sur SES trajets
        $reservations = DB::table('Reservations as r')
            ->join('Trajets as t', 'r.IdTrajet', '=', 't.IdTrajet')
            ->join('Utilisateurs as u', 'r.IdPassager', '=', 'u.IdUtilisateur') // le passager
            ->select(
                'r.IdReservation',
                'r.PlacesReservees',
                't.IdTrajet',
                't.Depart',
                't.Destination',
                't.DateTrajet',
                't.HeureTrajet',
                't.Prix',
                'u.Nom as NomPassager',
                'u.Prenom as PrenomPassager'
            )
            ->where('t.IdConducteur', $utilisateurId)
            ->get();
    } else {
        // 🧳 Si c’est un passager : voir SES propres réservations
        $reservations = DB::table('Reservations as r')
            ->join('Trajets as t', 'r.IdTrajet', '=', 't.IdTrajet')
            ->join('Utilisateurs as u', 't.IdConducteur', '=', 'u.IdUtilisateur')
            ->select(
                'r.IdReservation',
                'r.PlacesReservees',
                't.IdTrajet',
                't.Depart',
                't.Destination',
                't.DateTrajet',
                't.HeureTrajet',
                't.Prix',
                'u.Nom as NomConducteur',
                'u.Prenom as PrenomConducteur'
            )
            ->where('r.IdPassager', $utilisateurId)
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
}
