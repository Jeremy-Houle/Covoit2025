<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrajetController extends Controller
{
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