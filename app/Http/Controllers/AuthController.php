<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\BienvenueMail;

class AuthController extends Controller
{
    public function afficherConnexion()
    {
        return view('connexion');
    }
    
    public function traiterConnexion(Request $request)
    {
        $courriel = $request->input('courriel');
        $motDePasse = $request->input('mot_de_passe');
        
        $utilisateur = DB::table('utilisateurs')
            ->where('Courriel', $courriel)
            ->first();
        
        if ($utilisateur && password_verify($motDePasse, $utilisateur->MotDePasse)) {
            session(['utilisateur_id' => $utilisateur->IdUtilisateur]);
            session(['utilisateur_nom' => $utilisateur->Nom]);
            session(['utilisateur_prenom' => $utilisateur->Prenom]);
            session(['utilisateur_role' => $utilisateur->Role]);
            
            return redirect('/accueil')->with('success', 'Connexion réussie ! Bienvenue ' . $utilisateur->Prenom);
        }
        
        return back()->with('error', 'Courriel ou mot de passe incorrect');
    }
    
    public function afficherInscription()
    {
        return view('inscription');
    }
    
    public function traiterInscription(Request $request)
    {
        $nomComplet = $request->input('nom_complet');
        $courriel = $request->input('courriel');
        $role = $request->input('role');
        $motDePasse = $request->input('mot_de_passe');
        $confirmerMotDePasse = $request->input('confirmer_mot_de_passe');
        
        if (empty($nomComplet) || empty($courriel) || empty($role) || empty($motDePasse)) {
            return back()->with('error', 'Tous les champs sont obligatoires');
        }
        
        $nomPrenom = explode(' ', $nomComplet, 2);
        $nom = $nomPrenom[0] ?? '';
        $prenom = $nomPrenom[1] ?? '';
        
        if ($motDePasse !== $confirmerMotDePasse) {
            return back()->with('error', 'Les mots de passe ne correspondent pas');
        }
        
        if (strlen($motDePasse) < 6) {
            return back()->with('error', 'Le mot de passe doit contenir au moins 6 caractères');
        }
        
        $existeUtilisateur = DB::table('utilisateurs')
            ->where('Courriel', $courriel)
            ->exists();
        
        if ($existeUtilisateur) {
            return back()->with('error', 'Un compte avec ce courriel existe déjà');
        }
        
        try {
            DB::table('utilisateurs')->insert([
                'Nom' => $nom,
                'Prenom' => $prenom,
                'Courriel' => $courriel,
                'Role' => $role,
                'MotDePasse' => Hash::make($motDePasse),
                'Solde' => 1000.00
            ]);

            $nouvelUtilisateur = DB::table('utilisateurs')
                ->where('Courriel', $courriel)
                ->first();

            try {
                // Envoyer l'email en queue (asynchrone) pour ne pas bloquer l'inscription
                Mail::to($courriel)->queue(new BienvenueMail($nouvelUtilisateur));
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email de bienvenue: ' . $e->getMessage());
            }
            
            return redirect('/connexion')->with('success', 'Compte créé avec succès ! Vous pouvez maintenant vous connecter');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du compte');
        }
    }
    
    public function deconnexion()
    {
        session()->flush();
        return redirect('/accueil')->with('success', 'Vous avez été déconnecté');
    }
}
