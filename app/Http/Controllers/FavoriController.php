<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Favori;

class FavoriController extends Controller
{
    /**
     * Récupérer tous les favoris de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $favoris = DB::table('favoris')
            ->where('IdUtilisateur', $userId)
            ->pluck('IdTrajet')
            ->map(function($id) {
                return (string) $id;
            })
            ->toArray();

        return response()->json($favoris);
    }

    /**
     * Ajouter ou retirer un trajet des favoris (toggle)
     */
    public function toggle(Request $request)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $request->validate([
            'IdTrajet' => 'required|integer|exists:Trajets,IdTrajet'
        ]);

        $idTrajet = (int) $request->input('IdTrajet');

        // Vérifier si le favori existe déjà
        $favori = DB::table('favoris')
            ->where('IdUtilisateur', $userId)
            ->where('IdTrajet', $idTrajet)
            ->first();

        if ($favori) {
            // Retirer des favoris
            DB::table('favoris')
                ->where('IdUtilisateur', $userId)
                ->where('IdTrajet', $idTrajet)
                ->delete();

            return response()->json([
                'success' => true,
                'isFavorite' => false,
                'message' => 'Trajet retiré des favoris'
            ]);
        } else {
            // Ajouter aux favoris en utilisant la procédure stockée
            try {
                // Essayer d'abord l'insertion directe avec gestion d'erreur pour les doublons
                try {
                    DB::table('favoris')->insert([
                        'IdUtilisateur' => $userId,
                        'IdTrajet' => $idTrajet,
                        'DateAjout' => now()
                    ]);
                } catch (\Exception $insertErr) {
                    // Si l'erreur est une contrainte unique (doublon), vérifier si ça existe déjà
                    if (strpos($insertErr->getMessage(), 'Duplicate entry') !== false || 
                        strpos($insertErr->getMessage(), 'UNIQUE constraint') !== false) {
                        $existeDeja = DB::table('favoris')
                            ->where('IdUtilisateur', $userId)
                            ->where('IdTrajet', $idTrajet)
                            ->exists();
                        
                        if ($existeDeja) {
                            return response()->json([
                                'success' => true,
                                'isFavorite' => true,
                                'message' => 'Trajet déjà en favoris'
                            ]);
                        }
                    }
                    
                    // Si ce n'est pas un doublon, essayer la procédure stockée
                    DB::statement("CALL ajouterFavori(?, ?)", [
                        $userId,
                        $idTrajet
                    ]);

                    // Vérifier que le favori a bien été ajouté
                    $verification = DB::table('favoris')
                        ->where('IdUtilisateur', $userId)
                        ->where('IdTrajet', $idTrajet)
                        ->exists();

                    if (!$verification) {
                        Log::error('La procédure n\'a pas ajouté le favori', [
                            'userId' => $userId,
                            'idTrajet' => $idTrajet
                        ]);
                        throw new \Exception('La procédure n\'a pas ajouté le favori');
                    }
                }

                // Vérifier que le favori a bien été ajouté
                $verification = DB::table('favoris')
                    ->where('IdUtilisateur', $userId)
                    ->where('IdTrajet', $idTrajet)
                    ->exists();

                if (!$verification) {
                    Log::error('Le favori n\'existe toujours pas après toutes les tentatives', [
                        'userId' => $userId,
                        'idTrajet' => $idTrajet
                    ]);
                    throw new \Exception('Impossible d\'ajouter le favori');
                }

                return response()->json([
                    'success' => true,
                    'isFavorite' => true,
                    'message' => 'Trajet ajouté aux favoris'
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'ajout aux favoris', [
                    'userId' => $userId,
                    'idTrajet' => $idTrajet,
                    'error' => $e->getMessage()
                ]);
                
                // Si la procédure échoue, essayer l'insertion directe
                try {
                    DB::table('favoris')->insert([
                        'IdUtilisateur' => $userId,
                        'IdTrajet' => $idTrajet,
                        'DateAjout' => now()
                    ]);

                    return response()->json([
                        'success' => true,
                        'isFavorite' => true,
                        'message' => 'Trajet ajouté aux favoris'
                    ]);
                } catch (\Exception $insertError) {
                    Log::error('Erreur lors de l\'insertion directe du favori', [
                        'userId' => $userId,
                        'idTrajet' => $idTrajet,
                        'error' => $insertError->getMessage()
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'error' => 'Erreur lors de l\'ajout aux favoris',
                        'message' => $insertError->getMessage()
                    ], 500);
                }
            }
        }
    }

    /**
     * Vérifier si un trajet est en favoris
     */
    public function check(Request $request, $idTrajet)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return response()->json(['isFavorite' => false]);
        }

        $exists = DB::table('favoris')
            ->where('IdUtilisateur', $userId)
            ->where('IdTrajet', $idTrajet)
            ->exists();

        return response()->json(['isFavorite' => $exists]);
    }
}

