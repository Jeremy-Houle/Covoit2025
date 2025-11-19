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
    public function index()
    {
        $utilisateurId = session('utilisateur_id');
        $role = session('utilisateur_role');

        if (!$utilisateurId) {
            return redirect('/connexion')->with('error', 'Veuillez vous connecter pour voir vos réservations.');
        }

        DB::table('reservations')
            ->whereNull('IdPassager')
            ->orWhere('PlacesReservees', '<=', 0)
            ->delete();

        if (strtolower($role) === 'conducteur') {
            $reservations = DB::table('trajets as t')
                ->join('reservations as r', 't.IdTrajet', '=', 'r.IdTrajet')
                ->leftJoin('Utilisateurs as u', 'r.IdPassager', '=', 'u.IdUtilisateur')
                ->where('t.IdConducteur', $utilisateurId)
                ->where('r.PlacesReservees', '>', 0)
                ->select(
                    'r.IdReservation',
                    'r.IdPassager',
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
                ->get();
        } else {
            $reservations = DB::table('reservations as r')
                ->join('trajets as t', 'r.IdTrajet', '=', 't.IdTrajet')
                ->join('utilisateurs as u', 't.IdConducteur', '=', 'u.IdUtilisateur')
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

    public function update(Request $request, $id)
    {
        $userId = session('utilisateur_id');
        if (!$userId) return redirect('/connexion');

        $request->validate([
            'PlacesReservees' => 'required|integer|min:1'
        ]);

        $reservation = DB::table('reservations')->where('IdReservation', $id)->first();
        if (!$reservation || $reservation->IdPassager != $userId) {
            return redirect('/mes-reservations')->with('error', 'Réservation introuvable.');
        }

        $trajet = DB::table('trajets')->where('IdTrajet', $reservation->IdTrajet)->first();
        if (!$trajet) {
            return redirect('/mes-reservations')->with('error', 'Trajet introuvable.');
        }

        $diff = $request->PlacesReservees - $reservation->PlacesReservees;

        if ($diff > 0) {
            if ($diff > $trajet->PlacesDisponibles) {
                return redirect('/mes-reservations')->with('error', 'Pas assez de places disponibles.');
            }

            $montant = $diff * $trajet->Prix;

            DB::table('paiements')->insert([
                'IdUtilisateur' => $userId,
                'IdTrajet' => $trajet->IdTrajet,
                'NombrePlaces' => $diff,
                'Montant' => $montant,
                'Statut' => 'En attente',
                'MethodePaiement' => 'Carte Crédit',
                'DateCreation' => Carbon::now()
            ]);

            return redirect('/cart')->with('success', 'Les places supplémentaires ont été ajoutées au panier. Veuillez finaliser le paiement pour confirmer.');
        }

        elseif ($diff < 0) {
            DB::table('reservations')->where('IdReservation', $id)
                ->update(['PlacesReservees' => $request->PlacesReservees]);

            DB::table('trajets')->where('IdTrajet', $trajet->IdTrajet)
                ->increment('PlacesDisponibles', abs($diff));

            try {
                $passager = DB::table('utilisateurs')->where('IdUtilisateur', $userId)->first();
                if ($passager) {
                    $updatedReservation = DB::table('reservations')->where('IdReservation', $id)->first();
                    Mail::to($passager->Courriel)->send(
                        new TrajetConfirmeMail($trajet, $passager, $updatedReservation, 'modified')
                    );
                }
            } catch (\Exception $e) {
                Log::error('Erreur email modification réservation : ' . $e->getMessage());
            }

            return redirect('/mes-reservations')->with('success', 'Nombre de places réduit. Aucun remboursement effectué.');
        }

        return redirect('/mes-reservations')->with('info', 'Aucune modification détectée.');
    }

    public function destroy($id)
    {
        $userId = session('utilisateur_id');
        if (!$userId) return redirect('/connexion');

        $reservation = DB::table('reservations')->where('IdReservation', $id)->first();
        if (!$reservation || $reservation->IdPassager != $userId) {
            return redirect('/mes-reservations')->with('error', 'Réservation introuvable.');
        }

        $trajet = DB::table('trajets')->where('IdTrajet', $reservation->IdTrajet)->first();
        $passager = DB::table('utilisateurs')->where('IdUtilisateur', $userId)->first();

        DB::table('trajets')->where('IdTrajet', $trajet->IdTrajet)
            ->increment('PlacesDisponibles', $reservation->PlacesReservees);

        try {
            if ($passager && $trajet) {
                Mail::to($passager->Courriel)->send(
                    new ReservationAnnuleeParPassagerMail($trajet, $passager, $reservation)
                );
            }
        } catch (\Exception $e) {
            Log::error('Erreur email annulation réservation : ' . $e->getMessage());
        }

        DB::table('reservations')->where('IdReservation', $id)->delete();

        return redirect('/mes-reservations')->with('success', 'Réservation annulée. Aucun remboursement effectué.');
    }

    public function store(Request $request)
    {
        $userId = Auth::id() ?: session('utilisateur_id');
        if (! $userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'IdTrajet' => 'required|integer|exists:trajets,IdTrajet',
            'PlacesReservees' => 'required|integer|min:1'
        ]);

        $idTrajet = (int) $request->input('IdTrajet');
        $places = (int) $request->input('PlacesReservees');

        try {
            $paiementId = DB::transaction(function () use ($userId, $idTrajet, $places) {
                $trajet = DB::table('trajets')->where('IdTrajet', $idTrajet)->first();
                if (! $trajet) {
                    throw new \Exception('Trajet introuvable');
                }

                $prixUnitaire = isset($trajet->Prix) ? floatval($trajet->Prix) : 0.0;
                $montant = round($prixUnitaire * $places, 2);

                return DB::table('paiements')->insertGetId([
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
