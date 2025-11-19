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

        $messages = \DB::table('lesmessages')
            ->where(function($q) use ($userId, $otherId) {
                $q->where('IdExpediteur', $userId)->where('IdDestinataire', $otherId);
            })
            ->orWhere(function($q) use ($userId, $otherId) {
                $q->where('IdExpediteur', $otherId)->where('IdDestinataire', $userId);
            })
            ->orderBy('DateEnvoi')
            ->get();

        $other = \DB::table('utilisateurs')->where('IdUtilisateur', $otherId)->first();
        $otherName = $other
            ? trim(($other->Prenom ?? '') . ' ' . ($other->Nom ?? ''))
            : "Utilisateur #{$otherId}";

        $currentUser = \DB::table('utilisateurs')->where('IdUtilisateur', $userId)->first();
        $currentUserName = $currentUser
            ? trim(($currentUser->Prenom ?? '') . ' ' . ($currentUser->Nom ?? ''))
            : "Vous";

        DB::table('lesmessages')
            ->where('IdDestinataire', $userId)
            ->where('IdExpediteur', $otherId)
            ->update(['MessageLu' => 1]);

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

        \DB::table('lesmessages')->insert([
            'IdExpediteur'   => $userId,
            'IdDestinataire' => $otherId,
            'LeMessage'      => $text,
            'DateEnvoi'      => Carbon::now('UTC')->format('Y-m-d H:i:s'),
            'MessageLu'      => 0,
        ]);

        return redirect()->route('message.show', $otherId);
    }

    public function index()
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        $messages = DB::table('lesmessages as m')
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
        $users = DB::table('utilisateurs')->whereIn('IdUtilisateur', $participantIds)->get()->keyBy('IdUtilisateur');

        $threads = [];
        foreach ($messages as $m) {
            $other = ($m->IdExpediteur == $userId) ? $m->IdDestinataire : $m->IdExpediteur;
            if (!isset($threads[$other])) {
                $userOther = $users->get($other);
                $m->otherName = trim(($userOther->Prenom ?? '') . ' ' . ($userOther->Nom ?? ''));
                $m->sentAt = Carbon::parse($m->DateEnvoi, 'UTC')->setTimezone(config('app.timezone', 'America/Toronto'))->locale('fr')->diffForHumans();
                $threads[$other] = $m;
            }
        }

        DB::table('lesmessages')
            ->where('IdDestinataire', $userId)
            ->update(['MessageLu' => 1]);

        return view('messages.index', compact('threads', 'messages'));
    }

    public function unreadCount()
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return response()->json(['count' => 0]);
        }

        $count = DB::table('lesmessages')
            ->where('IdDestinataire', $userId)
            ->where('MessageLu', 0)
            ->count();

        return response()->json(['count' => $count]);
    }
}

