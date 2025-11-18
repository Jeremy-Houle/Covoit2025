<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Comment;
use App\Models\Trajet;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idTrajet'     => 'required|integer|exists:trajets,IdTrajet',
            'commentaire'  => 'required|string|min:1|max:250',
        ]);

        $userId = session('utilisateur_id');
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié'
            ], 401);
        }

        $idTrajet  = intval($validated['idTrajet']);
        $comment   = $validated['commentaire'];

        \App\Models\Comments::create([
            'IdTrajet'       => $idTrajet,
            'IdUtilisateur'  => $userId,
            'Commentaire'    => $comment,
        ]);

        return redirect()
            ->route('trajets.index')
            ->with('success', 'Commentaire ajouté avec succès!');
    }

    public function create(Trajet $trajet)
    {
        return view('CreateComment', compact('trajet'));
    }

}
