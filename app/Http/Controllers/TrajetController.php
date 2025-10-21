<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Trajet;

class TrajetController extends Controller
{
    public function create()
    {
        return view('publier');
    }

    public function store(Request $request)
    {
        $request->validate([
            'IdConducteur' => 'required|integer',
            'NomConducteur' => 'required|string|max:50',
            'Distance' => 'required|numeric',
            'Depart' => 'required|string|max:150',
            'Destination' => 'required|string|max:150',
            'DateTrajet' => 'required|date',
            'HeureTrajet' => 'required',
            'PlacesDisponibles' => 'required|integer|min:1',
            'Prix' => 'required|numeric|min:0',
            'AnimauxAcceptes' => 'boolean',
            'TypeConversation' => 'nullable|string|max:20',
            'Musique' => 'nullable|boolean',
            'Fumeur' => 'nullable|boolean',
        ]);

        Trajet::create($request->all());

        return redirect()->route('trajets.index')->with('success', 'Trajet ajouté avec succès!');
    }

    public function index()
    {
        $trajets = Trajet::all();
        return view('rechercher', compact('trajets'));
    }

    public function search(Request $request)
    {
        // ne sélectionner que Depart et Destination
        $q = DB::table('Trajets')->select('*');

        if ($v = $request->input('Depart')) {
            $q->where('Depart', 'like', '%' . trim($v) . '%');
        }
        if ($v = $request->input('Destination')) {
            $q->where('Destination', 'like', '%' . trim($v) . '%');
        }
        if ($request->filled('Fumeur')) {
            $q->where('Fumeur', 1);
        }

        $trajets = $q->orderBy('DateTrajet','asc')->limit(100)->get();

        return response()->json($trajets);
    }

    // nouvelle méthode : réserver des places pour un trajet
    public function reserve(Request $request)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $request->validate([
            'IdTrajet' => 'required|integer|exists:Trajets,IdTrajet',
            'PlacesReservees' => 'nullable|integer|min:1'
        ]);

        $idTrajet = (int) $request->input('IdTrajet');
        $places = (int) $request->input('PlacesReservees', 1);

        try {
            $result = DB::transaction(function () use ($idTrajet, $places, $userId) {
                // Verifier places disponibles
                $trajet = DB::table('Trajets')->where('IdTrajet', $idTrajet)->lockForUpdate()->first();
                if (!$trajet) {
                    return ['error' => 'Trajet introuvable', 'status' => 404];
                }
                $dispo = (int) ($trajet->PlacesDisponibles ?? 0);
                if ($dispo < $places) {
                    return ['error' => 'Pas assez de places disponibles', 'status' => 422];
                }

                // Insérer réservation
                $idResa = DB::table('Reservations')->insertGetId([
                    'IdTrajet' => $idTrajet,
                    'IdPassager' => $userId,
                    'Distance' => null,
                    'PlacesReservees' => $places,
                    // DateReservation utilisera la valeur par défaut
                ]);

                // Décrémenter places disponibles
                DB::table('Trajets')
                    ->where('IdTrajet', $idTrajet)
                    ->update(['PlacesDisponibles' => DB::raw("PlacesDisponibles - {$places}")]);

                return ['success' => true, 'IdReservation' => $idResa, 'status' => 201];
            });

            if (isset($result['error'])) {
                return response()->json(['error' => $result['error']], $result['status']);
            }

            return response()->json(['message' => 'Réservation ajoutée', 'IdReservation' => $result['IdReservation']], 201);
        } catch (\Exception $e) {
            Log::error('Erreur réservation : ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur lors de la réservation'], 500);
        }
    }
}
}
