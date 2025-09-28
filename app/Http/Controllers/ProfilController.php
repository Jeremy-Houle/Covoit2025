<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
     // Afficher le formulaire d'édition
    public function edit()
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        $user = DB::table('Utilisateurs')->where('IdUtilisateur', $userId)->first();

        return view('edit-profil', compact('user'));
    }

    // Mettre à jour les informations utilisateur
public function update(Request $request)
{
    $userId = session('utilisateur_id');
    if (!$userId) {
        return redirect('/connexion');
    }

    $request->validate([
        'Prenom' => 'required|string|max:50',
        'Nom' => 'required|string|max:50',
        'Courriel' => 'required|email|unique:Utilisateurs,Courriel,' . $userId . ',IdUtilisateur',
        'MotDePasse' => 'nullable|min:6|confirmed',
    ]);

    $data = [
        'Prenom' => $request->Prenom,
        'Nom' => $request->Nom,
        'Courriel' => $request->Courriel,
    ];

    if ($request->MotDePasse) {
        $data['MotDePasse'] = Hash::make($request->MotDePasse);
    }

    DB::table('Utilisateurs')->where('IdUtilisateur', $userId)->update($data);

    // Redirige vers la page profil après sauvegarde
    return redirect('/profil')->with('success', 'Profil mis à jour avec succès.');
}
    public function index()
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        // Infos utilisateur
        $user = DB::table('Utilisateurs')->where('IdUtilisateur', $userId)->first();

        // Moyenne des notes si conducteur
        $moyenneNote = DB::table('Evaluation as e')
            ->join('Trajets as t', 'e.IdTrajet', '=', 't.IdTrajet')
            ->where('t.IdConducteur', $userId)
            ->avg('e.Note') ?? 0;

        // Prochaines réservations
        if ($user->Role === 'Passager') {
            $prochainesReservations = DB::table('Reservations as r')
                ->join('Trajets as t', 'r.IdTrajet', '=', 't.IdTrajet')
                ->where('r.IdPassager', $userId)
                ->where('t.DateTrajet', '>=', Carbon::today())
                ->orderBy('r.IdReservation', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($resa) {
                    $passagers = DB::table('Reservations')
                        ->where('IdTrajet', $resa->IdTrajet)
                        ->join('Utilisateurs', 'Reservations.IdPassager', '=', 'Utilisateurs.IdUtilisateur')
                        ->select('Utilisateurs.Prenom', 'Utilisateurs.Nom')
                        ->get();
                    $resa->passagers = $passagers;
                    return $resa;
                });
        } else { // Conducteur
            $prochainesReservations = DB::table('Trajets as t')
                ->where('t.IdConducteur', $userId)
                ->where('t.DateTrajet', '>=', Carbon::today())
                ->orderBy('t.DateTrajet', 'asc')
                ->limit(5)
                ->get()
                ->map(function ($trajet) {
                    $reservations = DB::table('Reservations')
                        ->where('IdTrajet', $trajet->IdTrajet)
                        ->join('Utilisateurs', 'Reservations.IdPassager', '=', 'Utilisateurs.IdUtilisateur')
                        ->select('Reservations.PlacesReservees', 'Utilisateurs.Prenom', 'Utilisateurs.Nom')
                        ->get();
                    $trajet->PlacesReservees = $reservations->sum('PlacesReservees');
                    $trajet->passagers = $reservations;

                    // Récupérer les commentaires pour ce trajet
                    $trajet->commentaires = DB::table('Commentaires')
                        ->where('IdTrajet', $trajet->IdTrajet)
                        ->join('Utilisateurs', 'Commentaires.IdUtilisateur', '=', 'Utilisateurs.IdUtilisateur')
                        ->select('Commentaires.Commentaire', 'Utilisateurs.Prenom', 'Utilisateurs.Nom')
                        ->get();

                    return $trajet;
                });
        }

        // Messages récents
        $messagesRecents = DB::table('LesMessages as m')
            ->join('Utilisateurs as u', 'm.IdExpediteur', '=', 'u.IdUtilisateur')
            ->where('m.IdDestinataire', $userId)
            ->orderBy('m.DateEnvoi', 'desc')
            ->limit(5)
            ->select('m.*', 'u.Prenom', 'u.Nom')
            ->get();

        // Activités (paiements + activités propres)
        $activites = [];

        // Paiements
        $paiements = DB::table('Paiements')
            ->where('IdUtilisateur', $userId)
            ->orderBy('DateCreation', 'desc')
            ->limit(3)
            ->get();

        foreach ($paiements as $p) {
            $activites[] = [
                'couleur' => 'green',
                'titre' => 'Paiement',
                'description' => "Paiement de {$p->Montant} $ pour le trajet #{$p->IdTrajet}",
            ];
        }

        // Activités réelles liées à l'utilisateur
        $userActivites = DB::table('Activites')
            ->where('IdUtilisateur', $userId)
            ->orderBy('DateActivite', 'desc')
            ->limit(3)
            ->get();

        foreach ($userActivites as $act) {
            $activites[] = [
                'couleur' => 'blue',
                'titre' => $act->Titre,
                'description' => $act->Description,
            ];
        }

        // Note moyenne si conducteur
        if ($moyenneNote > 0) {
            $activites[] = [
                'couleur' => 'orange',
                'titre' => 'Nouvel avis',
                'description' => "Vous avez reçu une note moyenne de " . number_format($moyenneNote, 1) . " / 5",
            ];
        }

        return view('profil', compact(
            'user',
            'moyenneNote',
            'prochainesReservations',
            'messagesRecents',
            'activites'
        ));
    }
}
