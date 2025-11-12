<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
public function store(Request $request)
{
    $validated = $request->validate([
        'idTrajet' => 'required|integer|exists:trajets,IdTrajet',
        'note' => 'required|integer|min:1|max:5',
    ]);
    $userId = session('utilisateur_id');
    if (!$userId) {
        return response()->json(['success' => false, 'message' => 'Utilisateur non authentifié'], 401);
    }

    $idTrajet = intval($validated['idTrajet']);
    $note = intval($validated['note']);

    $existing = \App\Models\Review::where('IdTrajet', $idTrajet)->where('IdUtilisateur', $userId)->first();

    $action = null;
    $user_note = null;

    if ($existing) {
        if ((int)$existing->Note === $note) {
            $existing->delete();
            $action = 'deleted';
            $user_note = null;
        } else {
            $existing->Note = $note;
            $existing->save();
            $action = 'updated';
            $user_note = $note;
        }
        $review = $existing;
    } else {
        $review = \App\Models\Review::create([
            'IdTrajet' => $idTrajet,
            'IdUtilisateur' => $userId,
            'Note' => $note,
        ]);
        $action = 'created';
        $user_note = $note;
    }

    $avg = DB::table('evaluation')->where('IdTrajet', $idTrajet)->avg('Note');
    $count = DB::table('evaluation')->where('IdTrajet', $idTrajet)->count();

    return response()->json([
        'success' => true,
        'action' => $action,
        'review' => $review,
        'user_note' => $user_note,
        'average_note' => $avg !== null ? floatval($avg) : 0,
        'review_count' => intval($count),
    ]);
}

}
