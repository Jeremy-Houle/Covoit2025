<?php
/*namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Mail\TrajetConfirmeMail;
use App\Mail\ReservationAnnuleeParPassagerMail;

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
        $utilisateurId = session('utilisateur_id');
        $role = session('utilisateur_role');

        if (!$utilisateurId) {
            return redirect('/connexion')->with('error', 'Veuillez vous connecter pour voir vos réservations.');
        }

        if ($role === 'Conducteur') {
            $reservations = DB::table('Trajets as t')
                ->leftJoin('Reservations as r', 't.IdTrajet', '=', 'r.IdTrajet')
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

    public function update(Request $request, $id)
    {
        $userId = session('utilisateur_id');
        if (!$userId)
            return redirect('/connexion');

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
        if (!$userId)
            return redirect('/connexion');

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
*/
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
    public function index()
    {
        $utilisateurId = session('utilisateur_id');
        $role = session('utilisateur_role');

        if (!$utilisateurId) {
            return redirect('/connexion')->with('error', 'Veuillez vous connecter pour voir vos réservations.');
        }

        // 🔹 Supprimer d'abord les réservations vides (au cas où il reste des lignes sans passagers)
        DB::table('Reservations')
            ->whereNull('IdPassager')
            ->orWhere('PlacesReservees', '<=', 0)
            ->delete();

        if ($role === 'Conducteur') {
            // 🔹 Ne montrer que les trajets avec au moins une réservation active
            $reservations = DB::table('Trajets as t')
                ->join('Reservations as r', 't.IdTrajet', '=', 'r.IdTrajet') // join (et non leftJoin)
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
                ->havingRaw('SUM(r.PlacesReservees) > 0') // ⚡ affiche uniquement les trajets avec réservations
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
        if (!$userId)
            return redirect('/connexion');

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
        if (!$userId)
            return redirect('/connexion');

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

        // 🔹 Supprimer la réservation
        DB::table('Reservations')->where('IdReservation', $id)->delete();

        // 🔹 Vérifier si le trajet a encore des réservations
        $remaining = DB::table('Reservations')
            ->where('IdTrajet', $reservation->IdTrajet)
            ->count();

        // 🔹 Si plus aucune réservation → supprimer toutes les entrées résiduelles (par sécurité)
        if ($remaining === 0) {
            DB::table('Reservations')
                ->where('IdTrajet', $reservation->IdTrajet)
                ->delete();
        }

        return redirect('/mes-reservations')->with('success', 'Réservation annulée.');
    }
}
