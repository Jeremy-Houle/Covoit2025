<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LesMessageController extends Controller
{
    public function show($id)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        $otherId = (int) $id;

        // récupérer la conversation entre $userId et $otherId
        $messages = \DB::table('LesMessages')
            ->where(function($q) use ($userId, $otherId) {
                $q->where('IdExpediteur', $userId)->where('IdDestinataire', $otherId);
            })
            ->orWhere(function($q) use ($userId, $otherId) {
                $q->where('IdExpediteur', $otherId)->where('IdDestinataire', $userId);
            })
            ->orderBy('DateEnvoi')
            ->get();

        // récupérer les infos de l'autre utilisateur
        $other = \DB::table('Utilisateurs')->where('IdUtilisateur', $otherId)->first();
        $otherName = $other
            ? trim(($other->Prenom ?? '') . ' ' . ($other->Nom ?? ''))
            : "Utilisateur #{$otherId}";

        return view('messages.show', compact('messages', 'otherId', 'otherName'));
    }

    public function store(Request $request, $id)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        $otherId = (int) $id;
        $text = trim($request->input('message', ''));
        if ($text === '') {
            return redirect()->route('message.show', $otherId);
        }

        // stocker la date/heure selon le timezone de l'application
        \DB::table('LesMessages')->insert([
            'IdExpediteur'   => $userId,
            'IdDestinataire' => $otherId,
            'LeMessage'      => $text,
            'DateEnvoi'      => Carbon::now('UTC')->format('Y-m-d H:i:s'), // stocker en UTC
            // ajouter autres colonnes nécessaires...
        ]);

        return redirect()->route('message.show', $otherId);
    }

    public function index()
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        // récupérer tous les messages où l'utilisateur est participant (ordre décroissant)
        $messages = DB::table('LesMessages as m')
            ->select('m.*')
            ->where('m.IdExpediteur', $userId)
            ->orWhere('m.IdDestinataire', $userId)
            ->orderByDesc('m.DateEnvoi')
            ->get();

        // collecter les ids de participants puis charger leurs infos
        $participantIds = [];
        foreach ($messages as $m) {
            $participantIds[] = $m->IdExpediteur;
            $participantIds[] = $m->IdDestinataire;
        }
        $participantIds = array_values(array_unique($participantIds));
        $users = DB::table('Utilisateurs')->whereIn('IdUtilisateur', $participantIds)->get()->keyBy('IdUtilisateur');

        // construire threads : clé = autre participant, valeur = dernier message
        $threads = [];
        foreach ($messages as $m) {
            $other = ($m->IdExpediteur == $userId) ? $m->IdDestinataire : $m->IdExpediteur;
            if (!isset($threads[$other])) {
                $userOther = $users->get($other);
                $m->otherName = ($userOther->Nom ?? '') . ' ' . ($userOther->Prenom ?? '');
                // format simple date+heure
                $m->sentAt = \Carbon\Carbon::parse($m->DateEnvoi)->locale('fr')->translatedFormat('j F Y H:i');
                $threads[$other] = $m;
            }
        }

        return view('messages.index', compact('threads', 'messages'));
    }
}

