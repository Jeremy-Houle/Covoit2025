<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $typeFavori = $request->input('type');

        if (!$typeFavori) {
            return response()->json([]);
        }

        $favoris = DB::table('favoris')
            ->where('IdUtilisateur', $userId)
            ->where('TypeFavori', $typeFavori)
            ->pluck('IdTrajet')
            ->map(function($id) {
                return (string) $id;
            })
            ->toArray();

        return response()->json($favoris);
    }

    public function toggle(Request $request)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $request->validate([
            'IdTrajet' => 'required|integer|exists:trajets,IdTrajet',
            'TypeFavori' => 'required|string|in:Rechercher,reserver'
        ]);

        $idTrajet = (int) $request->input('IdTrajet');
        $typeFavori = $request->input('TypeFavori');

        $favori = DB::table('favoris')
            ->where('IdUtilisateur', $userId)
            ->where('IdTrajet', $idTrajet)
            ->where('TypeFavori', $typeFavori)
            ->first();

        if ($favori) {
            DB::table('favoris')
                ->where('IdUtilisateur', $userId)
                ->where('IdTrajet', $idTrajet)
                ->where('TypeFavori', $typeFavori)
                ->delete();

            return response()->json([
                'success' => true,
                'isFavorite' => false,
                'message' => 'Trajet retiré des favoris'
            ]);
        } else {
            try {
                DB::statement("CALL AjouterFavori(?, ?, ?)", [
                    $userId,
                    $idTrajet,
                    $typeFavori
                ]);

                $verification = DB::table('favoris')
                    ->where('IdUtilisateur', $userId)
                    ->where('IdTrajet', $idTrajet)
                    ->where('TypeFavori', $typeFavori)
                    ->exists();

                if (!$verification) {
                    throw new \Exception('La procédure n\'a pas ajouté le favori');
                }

                return response()->json([
                    'success' => true,
                    'isFavorite' => true,
                    'message' => 'Trajet ajouté aux favoris'
                ]);
            } catch (\Exception $e) {
                try {
                    $existeDeja = DB::table('favoris')
                        ->where('IdUtilisateur', $userId)
                        ->where('IdTrajet', $idTrajet)
                        ->where('TypeFavori', $typeFavori)
                        ->exists();
                    
                    if (!$existeDeja) {
                        DB::table('favoris')->insert([
                            'IdUtilisateur' => $userId,
                            'IdTrajet' => $idTrajet,
                            'TypeFavori' => $typeFavori,
                            'DateAjout' => now()
                        ]);
                    }

                    return response()->json([
                        'success' => true,
                        'isFavorite' => true,
                        'message' => 'Trajet ajouté aux favoris'
                    ]);
                } catch (\Exception $insertError) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Erreur lors de l\'ajout aux favoris',
                        'message' => $insertError->getMessage()
                    ], 500);
                }
            }
        }
    }

    public function check(Request $request, $idTrajet)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return response()->json(['isFavorite' => false]);
        }

        $typeFavori = $request->input('type');

        $query = DB::table('favoris')
            ->where('IdUtilisateur', $userId)
            ->where('IdTrajet', $idTrajet);
        
        if ($typeFavori) {
            $query->where('TypeFavori', $typeFavori);
        }

        $exists = $query->exists();

        return response()->json(['isFavorite' => $exists]);
    }
}
