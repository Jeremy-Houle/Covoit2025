<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\Trajet;
use App\Mail\TrajetConfirmeMail;
use App\Mail\TrajetAnnuleMail;

class TrajetController extends Controller
{
    private function normalizeString($string)
    {
        $string = strtolower(trim($string));

        $string = str_replace(
            [
                'é',
                'è',
                'ê',
                'ë',
                'à',
                'â',
                'ä',
                'ô',
                'ö',
                'û',
                'ü',
                'ù',
                'î',
                'ï',
                'ç',
                'É',
                'È',
                'Ê',
                'Ë',
                'À',
                'Â',
                'Ä',
                'Ô',
                'Ö',
                'Û',
                'Ü',
                'Ù',
                'Î',
                'Ï',
                'Ç'
            ],
            [
                'e',
                'e',
                'e',
                'e',
                'a',
                'a',
                'a',
                'o',
                'o',
                'u',
                'u',
                'u',
                'i',
                'i',
                'c',
                'e',
                'e',
                'e',
                'e',
                'a',
                'a',
                'a',
                'o',
                'o',
                'u',
                'u',
                'u',
                'i',
                'i',
                'c'
            ],
            $string
        );

        $string = preg_replace('/[^a-z0-9]/', '', $string);

        return $string;
    }

    public function create()
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect('/connexion')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }
        $role = session('utilisateur_role');
        if($role == "Passager"){
            return redirect('/');
        }

        $mesTrajets = DB::table('trajets')->where('IdConducteur', $userId)->get();

        $mesFavoris = DB::table('trajet_favoris')->where('IdUtilisateur', $userId)->get();

        return view('publier', compact('mesTrajets', 'mesFavoris'));
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
            'RappelEmail' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['RappelEmail'] = $request->has('RappelEmail') ? 1 : 0;
        $data['RappelEnvoye'] = 0;

        $trajet = Trajet::create($data);

        if ($request->has('RappelEmail')) {
            $dateTimeTrajet = \Carbon\Carbon::parse($request->DateTrajet . ' ' . $request->HeureTrajet);
            $now = \Carbon\Carbon::now();
            $diffEnHeures = $now->diffInHours($dateTimeTrajet, false);

            if ($diffEnHeures >= 0 && $diffEnHeures <= 2) {
                try {
                    $conducteur = DB::table('utilisateurs')
                        ->where('IdUtilisateur', $request->IdConducteur)
                        ->first();

                    if ($conducteur) {
                        $emailData = [
                            'conducteurNom' => $conducteur->Prenom . ' ' . $conducteur->Nom,
                            'depart' => $request->Depart,
                            'destination' => $request->Destination,
                            'dateTrajet' => \Carbon\Carbon::parse($request->DateTrajet)->format('d/m/Y'),
                            'heureTrajet' => \Carbon\Carbon::parse($request->HeureTrajet)->format('H:i'),
                            'placesDisponibles' => $request->PlacesDisponibles,
                        ];

                        $heuresRestantes = round(\Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::parse(request()->DateTrajet . ' ' . request()->HeureTrajet)));

                        \Mail::queue('emails.rappel-trajet', $emailData, function ($message) use ($conducteur, $heuresRestantes) {
                            $message->to($conducteur->Courriel)
                                ->subject('🚗 Rappel : Votre trajet dans ' . $heuresRestantes . 'h - Covoit2025');
                        });

                        DB::table('trajets')
                            ->where('IdTrajet', $trajet->IdTrajet)
                            ->update(['RappelEnvoye' => true]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de l\'envoi du rappel immédiat: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('trajets.create')->with('success', 'Trajet ajouté avec succès!');
    }
   
    public function search(Request $request)
    {
        $query = DB::table('trajets')->select('*');

        if ($depart = $request->input('Depart')) {
            $departNormalized = $this->normalizeString($depart);
            $query->whereRaw('LOWER(Depart) LIKE ?', ['%' . strtolower($depart) . '%']);
        }

        if ($destination = $request->input('Destination')) {
            $destinationNormalized = $this->normalizeString($destination);
            $query->whereRaw('LOWER(Destination) LIKE ?', ['%' . strtolower($destination) . '%']);
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

        $query->where('PlacesDisponibles', '>', 0);

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


        if ($request->input('ShortestPath') == '1') {

            $query->orderByRaw('CAST(Distance AS DECIMAL(10,2)) ASC');
        } else {

            $query->orderBy('DateTrajet', 'asc');
        }

        $trajets = $query->limit(100)->get();

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {

            $trajetIds = $trajets->pluck('IdTrajet')->toArray();
            $commentCounts = DB::table('commentaires')
                ->select('IdTrajet', DB::raw('COUNT(*) as comment_count'))
                ->whereIn('IdTrajet', $trajetIds)
                ->groupBy('IdTrajet')
                ->pluck('comment_count', 'IdTrajet');
            
            $trajets = $trajets->map(function($trajet) use ($commentCounts) {
                $trajet->comment_count = $commentCounts[$trajet->IdTrajet] ?? 0;
                return $trajet;
            });
            
            return response()->json($trajets);
        }

        $reviews = DB::table('evaluation')
            ->select('IdTrajet', DB::raw('AVG(Note) as average_note'), DB::raw('COUNT(*) as review_count'))
            ->groupBy('IdTrajet')
            ->get()
            ->keyBy('IdTrajet');

        $commentsByTrajet = DB::table('commentaires')
            ->join('utilisateurs', 'commentaires.IdUtilisateur', '=', 'utilisateurs.IdUtilisateur')
            ->leftJoin('evaluation', function ($join) {
                $join->on('commentaires.IdUtilisateur', '=', 'evaluation.IdUtilisateur')
                    ->on('commentaires.IdTrajet', '=', 'evaluation.IdTrajet');
            })
            ->select(
                'commentaires.*',
                'utilisateurs.Prenom as user_prenom',
                'utilisateurs.Nom as user_nom',
                'evaluation.Note'
            )
            ->orderBy('DateCommentaire', 'desc')
            ->get()
            ->groupBy('IdTrajet');


        return view('rechercher', compact('trajets', 'reviews', 'commentsByTrajet'));
    }

    public function index()
    {
        $role = session('utilisateur_role');
        if ($role === 'Conducteur') {
            return redirect('/publier')->with('error', 'La page de recherche est réservée aux passagers.');
        }
        
        $trajets = DB::table('trajets')
        ->where('PlacesDisponibles', '>', 0) 
        ->get();        
        

        $reviews = DB::table('evaluation')
            ->select('IdTrajet', DB::raw('AVG(Note) as average_note'), DB::raw('COUNT(*) as review_count'))
            ->groupBy('IdTrajet')
            ->get()
            ->keyBy('IdTrajet');


        $commentsByTrajet = DB::table('commentaires')
            ->join('utilisateurs', 'commentaires.IdUtilisateur', '=', 'utilisateurs.IdUtilisateur')
            ->leftJoin('evaluation', function ($join) {
                $join->on('commentaires.IdUtilisateur', '=', 'evaluation.IdUtilisateur')
                    ->on('commentaires.IdTrajet', '=', 'evaluation.IdTrajet');
            })
            ->select(
                'commentaires.*',
                'utilisateurs.Prenom as user_prenom',
                'utilisateurs.Nom as user_nom',
                'evaluation.Note'
            )
            ->orderBy('DateCommentaire', 'desc')
            ->get()
            ->groupBy('IdTrajet');

        return view('rechercher', compact('trajets', 'reviews', 'commentsByTrajet'));
    }


    public function reserve(Request $request)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $request->validate([
            'IdTrajet' => 'required|integer|exists:trajets,IdTrajet',
            'PlacesReservees' => 'nullable|integer|min:1'
        ]);

        $idTrajet = (int) $request->input('IdTrajet');
        $places = (int) $request->input('PlacesReservees', 1);

        try {
            $result = DB::transaction(function () use ($idTrajet, $places, $userId) {
                $trajet = DB::table('trajets')->where('IdTrajet', $idTrajet)->lockForUpdate()->first();

                if (!$trajet) {
                    return ['error' => 'Trajet introuvable', 'status' => 404];
                }

                $dispo = (int) ($trajet->PlacesDisponibles ?? 0);
                if ($dispo < $places) {
                    return ['error' => 'Pas assez de places disponibles', 'status' => 422];
                }

                $idReservation = DB::table('reservations')->insertGetId([
                    'IdTrajet' => $idTrajet,
                    'IdPassager' => $userId,
                    'Distance' => null,
                    'PlacesReservees' => $places,
                ]);

                DB::table('trajets')
                    ->where('IdTrajet', $idTrajet)
                    ->update(['PlacesDisponibles' => DB::raw("PlacesDisponibles - {$places}")]);

                try {
                    $passager = DB::table('utilisateurs')->where('IdUtilisateur', $userId)->first();
                    $trajetInfo = DB::table('trajets')->where('IdTrajet', $idTrajet)->first();

                    if ($passager && $trajetInfo) {
                        $reservation = (object) [
                            'IdReservation' => $idReservation,
                            'PlacesReservees' => $places
                        ];

                        Mail::to($passager->Courriel)->queue(
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

        $trajet = DB::table('trajets')->where('IdTrajet', $id)->first();

        if (!$trajet || $trajet->IdConducteur != $userId) {
            return redirect('/mes-reservations')->with('error', 'Trajet introuvable ou vous n’êtes pas le conducteur.');
        }

        $request->validate([
            'DateTrajet' => 'required|date',
            'HeureTrajet' => 'required',
            'Prix' => 'required|numeric|min:0',
        ]);

        DB::table('trajets')->where('IdTrajet', $id)->update([
            'DateTrajet' => $request->DateTrajet,
            'HeureTrajet' => $request->HeureTrajet,
            'Prix' => $request->Prix,
        ]);

        $trajetUpdated = DB::table('trajets')->where('IdTrajet', $id)->first();
        $conducteur = DB::table('utilisateurs')->where('IdUtilisateur', $userId)->first();

        $reservations = DB::table('reservations')
            ->join('utilisateurs', 'reservations.IdPassager', '=', 'utilisateurs.IdUtilisateur')
            ->where('reservations.IdTrajet', $id)
            ->select('utilisateurs.*', 'reservations.*')
            ->get();

        foreach ($reservations as $resa) {
            try {
                Mail::to($resa->Courriel)->queue(
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

        $trajet = DB::table('trajets')->where('IdTrajet', $id)->first();
        if (!$trajet || $trajet->IdConducteur != $userId) {
            return redirect('/mes-reservations')->with('error', 'Trajet introuvable ou vous n’êtes pas le conducteur.');
        }

        $activePaymentsCount = DB::table('paiements')
            ->where('IdTrajet', $id)
            ->whereNotIn('Statut', ['En attente', 'Annulé'])
            ->count();

        if ($activePaymentsCount > 0) {
            return redirect('/publier')
                ->with('error', 'Impossible de supprimer ce trajet : certains paiements sont déjà effectués.');
        }

        DB::table('commentaires')->where('IdTrajet', $id)->delete();
        DB::table('evaluation')->where('IdTrajet', $id)->delete();
        DB::table('recurrencetrajet')->where('IdTrajet', $id)->delete();

        if (Schema::hasTable('favoris')) {
            DB::table('favoris')->where('IdTrajet', $id)->delete();
        }

        DB::table('reservations')->where('IdTrajet', $id)->delete();

        DB::table('paiements')
            ->where('IdTrajet', $id)
            ->whereIn('Statut', ['En attente', 'Annulé'])
            ->delete();

        DB::table('trajets')->where('IdTrajet', $id)->delete();

        return redirect('/publier')->with('success', 'Trajet supprimé avec succès.');
    }

    public function addToFavorites(Request $request)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect()->back()->with('error', 'Vous devez être connecté pour ajouter un trajet à vos favoris.');
        }

        $validated = $request->validate([
            'IdTrajet' => 'required|exists:trajets,IdTrajet',
        ]);

        $trajet = Trajet::find($validated['IdTrajet']);

        $favoriExiste = DB::table('trajet_favoris')
            ->where('IdUtilisateur', $userId)
            ->where('Depart', $trajet->Depart)
            ->where('Destination', $trajet->Destination)
            ->exists();

        if ($favoriExiste) {
            return redirect()->back()->with('error', 'Ce trajet est déjà dans vos favoris.');
        }

        DB::table('trajet_favoris')->insert([
            'IdUtilisateur' => $userId,
            'Depart' => $trajet->Depart,
            'Destination' => $trajet->Destination,
            'DateDernierePublication' => $trajet->DateTrajet,
            'HeureTrajet' => $trajet->HeureTrajet,
            'PlacesDisponibles' => $trajet->PlacesDisponibles,
            'Prix' => $trajet->Prix,
            'AnimauxAcceptes' => $trajet->AnimauxAcceptes,
            'TypeConversation' => $trajet->TypeConversation,
            'Musique' => $trajet->Musique,
            'Fumeur' => $trajet->Fumeur,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Trajet ajouté à vos favoris.');
    }

    public function deleteFavorite($id)
    {
        $userId = session('utilisateur_id');
        if (!$userId) {
            return redirect()->back()->with('error', 'Vous devez être connecté pour effectuer cette action.');
        }

        $favori = DB::table('trajet_favoris')->where('IdFavori', $id)->where('IdUtilisateur', $userId)->first();

        if (!$favori) {
            return redirect()->back()->with('error', 'Trajet sauvegardé introuvable.');
        }

        DB::table('trajet_favoris')->where('IdFavori', $id)->delete();

        return redirect()->back()->with('success', 'Trajet sauvegardé supprimé avec succès.');
    }
}
