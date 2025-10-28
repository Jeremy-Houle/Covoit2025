<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Trajet;
use App\Mail\TrajetConfirmeMail;
use App\Mail\TrajetAnnuleMail;

class TrajetController extends Controller
{
    private function normalizeString($string)
    {
        $string = strtolower(trim($string));
        
        $string = str_replace(
            ['é', 'è', 'ê', 'ë', 'à', 'â', 'ä', 'ô', 'ö', 'û', 'ü', 'ù', 'î', 'ï', 'ç',
             'É', 'È', 'Ê', 'Ë', 'À', 'Â', 'Ä', 'Ô', 'Ö', 'Û', 'Ü', 'Ù', 'Î', 'Ï', 'Ç'],
            ['e', 'e', 'e', 'e', 'a', 'a', 'a', 'o', 'o', 'u', 'u', 'u', 'i', 'i', 'c',
             'e', 'e', 'e', 'e', 'a', 'a', 'a', 'o', 'o', 'u', 'u', 'u', 'i', 'i', 'c'],
            $string
        );
        
        $string = preg_replace('/[^a-z0-9]/', '', $string);
        
        return $string;
    }

    public function create()
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion')->with('error', 'Veuillez vous connecter pour publier un trajet.');
        }

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
            'AnimauxAcceptes' => 'nullable|boolean',
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
        $query = DB::table('Trajets')->select('*');

        if ($depart = $request->input('Depart')) {
            $departNormalized = $this->normalizeString($depart);
            $query->whereRaw(
                'REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                    LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Depart, "é", "e"), "è", "e"), "ê", "e"), "à", "a"), "ô", "o"), "û", "u")),
                    " ", ""), ",", ""), "-", ""), ".", ""), "(", ""), ")", ""), "qc", ""), "canada", ""), "québec", "quebec"), "montréal", "montreal")
                LIKE ?',
                ['%' . $departNormalized . '%']
            );
        }

        if ($destination = $request->input('Destination')) {
            $destinationNormalized = $this->normalizeString($destination);
            $query->whereRaw(
                'REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                    LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(Destination, "é", "e"), "è", "e"), "ê", "e"), "à", "a"), "ô", "o"), "û", "u")),
                    " ", ""), ",", ""), "-", ""), ".", ""), "(", ""), ")", ""), "qc", ""), "canada", ""), "québec", "quebec"), "montréal", "montreal")
                LIKE ?',
                ['%' . $destinationNormalized . '%']
            );
        }

        if ($date = $request->input('DateTrajet')) {
            $query->whereDate('DateTrajet', $date);
        }

        if (($prixMax = $request->input('PrixMax')) !== null && $prixMax !== '') {
            if (is_numeric($prixMax)) {
                $query->where('Prix', '<=', floatval($prixMax));
            }
        }

        if (($placesMin = $request->input('PlacesMin')) !== null && $placesMin !== '') {
            if (is_numeric($placesMin)) {
                $query->where('PlacesDisponibles', '>=', intval($placesMin));
            }
        }

        if ($type = $request->input('TypeConversation')) {
            $allowed = ['Silencieux', 'Normal', 'Bavard'];
            if (in_array($type, $allowed)) {
                $query->where('TypeConversation', $type);
            }
        }

        $animaux = $request->input('AnimauxAcceptes');
        if ($animaux !== null && $animaux !== '') {
            if (in_array($animaux, ['0', '1', 0, 1], true)) {
                $query->where('AnimauxAcceptes', intval($animaux));
            }
        }

        $musique = $request->input('Musique');
        if ($musique !== null && $musique !== '') {
            if (in_array($musique, ['0', '1', 0, 1], true)) {
                $query->where('Musique', intval($musique));
            }
        }

        $fumeur = $request->input('Fumeur');
        if ($fumeur !== null && $fumeur !== '') {
            if (in_array($fumeur, ['0', '1', 0, 1], true)) {
                $query->where('Fumeur', intval($fumeur));
            }
        }

        $trajets = $query->orderBy('DateTrajet', 'asc')->limit(100)->get();

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json($trajets);
        }

        return view('rechercher', compact('trajets'));
    }

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
                $trajet = DB::table('Trajets')->where('IdTrajet', $idTrajet)->lockForUpdate()->first();

                if (!$trajet) {
                    return ['error' => 'Trajet introuvable', 'status' => 404];
                }

                $dispo = (int) ($trajet->PlacesDisponibles ?? 0);
                if ($dispo < $places) {
                    return ['error' => 'Pas assez de places disponibles', 'status' => 422];
                }

                $idReservation = DB::table('Reservations')->insertGetId([
                    'IdTrajet' => $idTrajet,
                    'IdPassager' => $userId,
                    'Distance' => null,
                    'PlacesReservees' => $places,
                ]);

                DB::table('Trajets')
                    ->where('IdTrajet', $idTrajet)
                    ->update(['PlacesDisponibles' => DB::raw("PlacesDisponibles - {$places}")]);

                try {
                    $passager = DB::table('Utilisateurs')->where('IdUtilisateur', $userId)->first();
                    $trajetInfo = DB::table('Trajets')->where('IdTrajet', $idTrajet)->first();

                    if ($passager && $trajetInfo) {
                        $reservation = (object)[
                            'IdReservation' => $idReservation,
                            'PlacesReservees' => $places
                        ];

                        Mail::to($passager->Courriel)->send(
                            new TrajetConfirmeMail($trajetInfo, $passager, $reservation, 'confirmed')
                        );
                    }
                } catch (\Exception $e) {
                    Log::error("Erreur lors de l'envoi du courriel de confirmation : {$e->getMessage()}");
                }

                return ['success' => true, 'IdReservation' => $idReservation, 'status' => 201];
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

    public function updateTrajet(Request $request, $id)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        $trajet = DB::table('Trajets')->where('IdTrajet', $id)->first();

        if (!$trajet || $trajet->IdConducteur != $userId) {
            return redirect('/mes-reservations')->with('error', 'Trajet introuvable ou vous n’êtes pas le conducteur.');
        }

        $request->validate([
            'DateTrajet' => 'required|date',
            'HeureTrajet' => 'required',
            'Prix' => 'required|numeric|min:0',
        ]);

        DB::table('Trajets')->where('IdTrajet', $id)->update([
            'DateTrajet' => $request->DateTrajet,
            'HeureTrajet' => $request->HeureTrajet,
            'Prix' => $request->Prix,
        ]);

        $trajetUpdated = DB::table('Trajets')->where('IdTrajet', $id)->first();
        $conducteur = DB::table('Utilisateurs')->where('IdUtilisateur', $userId)->first();

        $reservations = DB::table('Reservations')
            ->join('Utilisateurs', 'Reservations.IdPassager', '=', 'Utilisateurs.IdUtilisateur')
            ->where('Reservations.IdTrajet', $id)
            ->select('Utilisateurs.*', 'Reservations.*')
            ->get();

        foreach ($reservations as $resa) {
            try {
                Mail::to($resa->Courriel)->send(
                    new TrajetAnnuleMail($trajetUpdated, $resa, $conducteur, 'modified')
                );
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'envoi de l'email de modification au passager {$resa->IdPassager} : {$e->getMessage()}");
            }
        }

        return redirect('/mes-reservations')->with('success', 'Trajet modifié et passagers notifiés.');
    }

    public function cancelTrajet($id)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion');
        }

        $trajet = DB::table('Trajets')->where('IdTrajet', $id)->first();

        if (!$trajet || $trajet->IdConducteur != $userId) {
            return redirect('/mes-reservations')->with('error', 'Trajet introuvable ou vous n’êtes pas le conducteur.');
        }

        $conducteur = DB::table('Utilisateurs')->where('IdUtilisateur', $userId)->first();

        $reservations = DB::table('Reservations')
            ->join('Utilisateurs', 'Reservations.IdPassager', '=', 'Utilisateurs.IdUtilisateur')
            ->where('Reservations.IdTrajet', $id)
            ->select('Utilisateurs.*', 'Reservations.*')
            ->get();

        foreach ($reservations as $resa) {
            try {
                Mail::to($resa->Courriel)->send(
                    new TrajetAnnuleMail($trajet, $resa, $conducteur, 'cancelled')
                );
            } catch (\Exception $e) {
                Log::error("Erreur lors de l'envoi de l'email d'annulation au passager {$resa->IdPassager} : {$e->getMessage()}");
            }
        }

        DB::table('Paiements')
            ->where('IdTrajet', $id)
            ->update(['Statut' => 'Annulé']);

        DB::table('Reservations')->where('IdTrajet', $id)->delete();
        DB::table('Trajets')->where('IdTrajet', $id)->delete();

        return redirect('/mes-reservations')->with('success', 'Trajet annulé et passagers notifiés. Les remboursements seront traités automatiquement.');
    }
}
