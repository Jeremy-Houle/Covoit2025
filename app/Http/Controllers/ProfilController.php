<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\InfoCompteModifieMail;

class ProfilController extends Controller
{
    public function edit()
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        $user = DB::table('utilisateurs')->where('IdUtilisateur', $userId)->first();

        return view('edit-profil', compact('user'));
    }

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
        'Role' => 'required|in:Passager,Conducteur',
    ]);

    $oldUser = DB::table('utilisateurs')->where('IdUtilisateur', $userId)->first();
    
    $data = [
        'Prenom' => $request->Prenom,
        'Nom' => $request->Nom,
        'Courriel' => $request->Courriel,
        'Role' => $request->Role,
    ];

    $changedFields = [];
    if ($oldUser->Prenom !== $request->Prenom) {
        $changedFields['Prénom'] = $request->Prenom;
    }
    if ($oldUser->Nom !== $request->Nom) {
        $changedFields['Nom'] = $request->Nom;
    }
    if ($oldUser->Courriel !== $request->Courriel) {
        $changedFields['Courriel'] = $request->Courriel;
    }
    if ($oldUser->Role !== $request->Role) {
        $changedFields['Rôle'] = $request->Role;
        session(['utilisateur_role' => $request->Role]);
    }

    if ($request->MotDePasse) {
        $data['MotDePasse'] = Hash::make($request->MotDePasse);
        $changedFields['Mot de passe'] = '••••••••';
    }

    DB::table('utilisateurs')->where('IdUtilisateur', $userId)->update($data);

    if (!empty($changedFields)) {
        $updatedUser = DB::table('utilisateurs')->where('IdUtilisateur', $userId)->first();
        
        try {
            $emailTo = isset($changedFields['Courriel']) ? $oldUser->Courriel : $updatedUser->Courriel;
            
            Mail::to($emailTo)->send(new InfoCompteModifieMail($updatedUser, $changedFields));
            
            if (isset($changedFields['Courriel'])) {
                Mail::to($updatedUser->Courriel)->send(new InfoCompteModifieMail($updatedUser, $changedFields));
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de modification: ' . $e->getMessage());
        }
    }

    return redirect('/profil')->with('success', 'Profil mis à jour avec succès.');
}
    public function index()
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        $user = DB::table('utilisateurs')->where('IdUtilisateur', $userId)->first();

        $moyenneNote = DB::table('evaluation as e')
            ->join('trajets as t', 'e.IdTrajet', '=', 't.IdTrajet')
            ->where('t.IdConducteur', $userId)
            ->avg('e.Note') ?? 0;

        if ($user->Role === 'Passager') {
            $prochainesReservations = DB::table('reservations as r')
                ->join('trajets as t', 'r.IdTrajet', '=', 't.IdTrajet')
                ->where('r.IdPassager', $userId)
                ->where('t.DateTrajet', '>=', Carbon::today())
                ->orderBy('r.IdReservation', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($resa) {
                    $passagers = DB::table('reservations')
                        ->where('IdTrajet', $resa->IdTrajet)
                        ->join('utilisateurs', 'reservations.IdPassager', '=', 'utilisateurs.IdUtilisateur')
                        ->select('Utilisateurs.Prenom', 'Utilisateurs.Nom')
                        ->get();
                    $resa->passagers = $passagers;
                    return $resa;
                });
        } else { 
            $prochainesReservations = DB::table('trajets as t')
                ->where('t.IdConducteur', $userId)
                ->where('t.DateTrajet', '>=', Carbon::today())
                ->orderBy('t.DateTrajet', 'asc')
                ->limit(5)
                ->get()
                ->map(function ($trajet) {
                    $reservations = DB::table('reservations')
                        ->where('IdTrajet', $trajet->IdTrajet)
                        ->join('utilisateurs', 'reservations.IdPassager', '=', 'utilisateurs.IdUtilisateur')
                        ->select('reservations.PlacesReservees', 'Utilisateurs.Prenom', 'Utilisateurs.Nom')
                        ->get();
                    $trajet->PlacesReservees = $reservations->sum('PlacesReservees');
                    $trajet->passagers = $reservations;

                    $trajet->commentaires = DB::table('commentaires')
                        ->where('IdTrajet', $trajet->IdTrajet)
                        ->join('utilisateurs', 'commentaires.IdUtilisateur', '=', 'utilisateurs.IdUtilisateur')
                        ->select('Commentaires.Commentaire', 'Utilisateurs.Prenom', 'Utilisateurs.Nom')
                        ->get();

                    return $trajet;
                });
        }

        $messagesRecents = DB::table('lesmessages as m')
            ->join('utilisateurs as u', 'm.IdExpediteur', '=', 'u.IdUtilisateur')
            ->where('m.IdDestinataire', $userId)
            ->orderBy('m.DateEnvoi', 'desc')
            ->limit(5)
            ->select('m.*', 'u.Prenom', 'u.Nom')
            ->get();

        $activites = [];

        $paiements = DB::table('paiements')
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

        $userActivites = DB::table('activites')
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
