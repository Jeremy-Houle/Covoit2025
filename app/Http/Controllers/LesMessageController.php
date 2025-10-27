<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LesMessageController extends Controller
{
    public function show($id)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        $destinataireId = (int) $id;
        $destinataire = DB::table('Utilisateurs')->where('IdUtilisateur', $destinataireId)->first();
        if (!$destinataire) {
            abort(404);
        }

        // récupérer les messages entre les deux utilisateurs et joindre le nom/prénom de l'expéditeur
        $messages = DB::table('LesMessages as m')
            ->leftJoin('Utilisateurs as u', 'm.IdExpediteur', '=', 'u.IdUtilisateur')
            ->select('m.*', 'u.Nom as ExpediteurNom', 'u.Prenom as ExpediteurPrenom')
            ->where(function($q) use ($userId, $destinataireId) {
                $q->where('m.IdExpediteur', $userId)->where('m.IdDestinataire', $destinataireId);
            })
            ->orWhere(function($q) use ($userId, $destinataireId) {
                $q->where('m.IdExpediteur', $destinataireId)->where('m.IdDestinataire', $userId);
            })
            ->orderBy('m.DateEnvoi', 'asc')
            ->get();

        return view('show', compact('messages', 'destinataire', 'destinataireId'));
    }

    public function store(Request $request)
    {
        $expediteur = session('utilisateur_id');
        if (!$expediteur) {
            return redirect('/connexion');
        }

        $request->validate([
            'IdDestinataire' => 'required|integer|exists:Utilisateurs,IdUtilisateur',
            'LeMessage' => 'required|string|max:2000'
        ]);

        DB::table('LesMessages')->insert([
            'IdExpediteur' => $expediteur,
            'IdDestinataire' => (int) $request->input('IdDestinataire'),
            'LeMessage' => $request->input('LeMessage')
            // DateEnvoi utilisera la valeur par défaut
        ]);

        return redirect()->route('messages.show', (int)$request->input('IdDestinataire'))->with('success', 'Message envoyé.');
    }
}

