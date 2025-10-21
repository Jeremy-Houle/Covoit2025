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
}
