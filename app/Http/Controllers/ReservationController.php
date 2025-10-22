<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\TrajetConfirmeMail;
use App\Mail\ReservationAnnuleeParPassagerMail;

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
    $role = session('utilisateur_role'); 

    if (!$utilisateurId) {
        return redirect('/connexion')->with('error', 'Veuillez vous connecter pour voir vos réservations.');
    }

    if ($role === 'Conducteur') {
        $reservations = DB::table('Reservations as r')
            ->join('Trajets as t', 'r.IdTrajet', '=', 't.IdTrajet')
            ->join('Utilisateurs as u', 'r.IdPassager', '=', 'u.IdUtilisateur')
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

    $oldPlaces = $reservation->PlacesReservees;
    $newPlaces = $request->PlacesReservees;
    $diff = $newPlaces - $oldPlaces;

    if ($diff > $trajet->PlacesDisponibles) {
        return redirect('/mes-reservations')->with('error', 'Pas assez de places disponibles.');
    }

    DB::table('Reservations')->where('IdReservation', $id)->update([
        'PlacesReservees' => $newPlaces
    ]);

    if ($diff > 0) {
        DB::table('Trajets')->where('IdTrajet', $reservation->IdTrajet)
            ->decrement('PlacesDisponibles', $diff);
    } elseif ($diff < 0) {
        DB::table('Trajets')->where('IdTrajet', $reservation->IdTrajet)
            ->increment('PlacesDisponibles', abs($diff));
    }

    try {
        $passager = DB::table('Utilisateurs')->where('IdUtilisateur', $userId)->first();
        $trajetInfo = DB::table('Trajets')->where('IdTrajet', $reservation->IdTrajet)->first();
        $updatedReservation = DB::table('Reservations')->where('IdReservation', $id)->first();
        
        if ($passager && $trajetInfo && $updatedReservation) {
            Mail::to($passager->Courriel)->send(
                new TrajetConfirmeMail($trajetInfo, $passager, $updatedReservation, 'modified')
            );
        }
    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'envoi de l\'email de modification: ' . $e->getMessage());
    }

    return redirect('/mes-reservations')->with('success', 'Réservation mise à jour.');
}

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

        DB::table('Trajets')
            ->where('IdTrajet', $reservation->IdTrajet)
            ->increment('PlacesDisponibles', $reservation->PlacesReservees);

        DB::table('Paiements')
            ->where('IdTrajet', $reservation->IdTrajet)
            ->where('IdUtilisateur', $userId)
            ->update(['Statut' => 'Annulé']);

        try {
            if ($passager && $trajet) {
                Mail::to($passager->Courriel)->send(
                    new ReservationAnnuleeParPassagerMail($trajet, $passager, $reservation)
                );
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email d\'annulation: ' . $e->getMessage());
        }
        
        DB::table('Reservations')->where('IdReservation', $id)->delete();

        return redirect('/mes-reservations')->with('success', 'Réservation annulée.');
    }
}
