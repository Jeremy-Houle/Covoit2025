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
            return redirect('/connexion')->with('error', 'Veuillez vous connecter pour consulter vos messages.');
        }

        $otherId = (int) $id;

        $messages = \DB::table('LesMessages')
            ->where(function($q) use ($userId, $otherId) {
                $q->where('IdExpediteur', $userId)->where('IdDestinataire', $otherId);
            })
            ->orWhere(function($q) use ($userId, $otherId) {
                $q->where('IdExpediteur', $otherId)->where('IdDestinataire', $userId);
            })
            ->orderBy('DateEnvoi')
            ->get();

        $other = \DB::table('Utilisateurs')->where('IdUtilisateur', $otherId)->first();
        $otherName = $other
            ? trim(($other->Prenom ?? '') . ' ' . ($other->Nom ?? ''))
            : "Utilisateur #{$otherId}";

        $currentUser = \DB::table('Utilisateurs')->where('IdUtilisateur', $userId)->first();
        $currentUserName = $currentUser
            ? trim(($currentUser->Prenom ?? '') . ' ' . ($currentUser->Nom ?? ''))
            : "Vous";

        return view('messages.show', compact('messages', 'otherId', 'otherName', 'currentUserName', 'userId'));
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

        \DB::table('LesMessages')->insert([
            'IdExpediteur'   => $userId,
            'IdDestinataire' => $otherId,
            'LeMessage'      => $text,
            'DateEnvoi'      => Carbon::now('UTC')->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('message.show', $otherId);
    }

    public function index()
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        $messages = DB::table('LesMessages as m')
            ->select('m.*')
            ->where('m.IdExpediteur', $userId)
            ->orWhere('m.IdDestinataire', $userId)
            ->orderByDesc('m.DateEnvoi')
            ->get();

        $participantIds = [];
        foreach ($messages as $m) {
            $participantIds[] = $m->IdExpediteur;
            $participantIds[] = $m->IdDestinataire;
        }
        $participantIds = array_values(array_unique($participantIds));
        $users = DB::table('Utilisateurs')->whereIn('IdUtilisateur', $participantIds)->get()->keyBy('IdUtilisateur');

        $threads = [];
        foreach ($messages as $m) {
            $other = ($m->IdExpediteur == $userId) ? $m->IdDestinataire : $m->IdExpediteur;
            if (!isset($threads[$other])) {
                $userOther = $users->get($other);
                $m->otherName = trim(($userOther->Prenom ?? '') . ' ' . ($userOther->Nom ?? ''));
                // format simple date+heure - convertir de UTC vers timezone local
                $m->sentAt = \Carbon\Carbon::parse($m->DateEnvoi, 'UTC')->setTimezone(config('app.timezone', 'America/Toronto'))->locale('fr')->diffForHumans();
                $threads[$other] = $m;
            }
        }

        return view('messages.index', compact('threads', 'messages'));
    }
}

