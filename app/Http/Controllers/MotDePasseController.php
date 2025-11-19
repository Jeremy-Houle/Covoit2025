<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ReinitialiserMotDePasseMail;

class MotDePasseController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.mot-de-passe-oublie');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = DB::table('utilisateurs')
            ->where('Courriel', $request->email)
            ->first();

        if (!$user) {
            return back()->with('error', 'Aucun compte trouvé avec cette adresse email.');
        }

        $token = Str::random(60);

        DB::table('password_resets')->where('email', $request->email)->delete();

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        try {
            Mail::to($request->email)->queue(new ReinitialiserMotDePasseMail($token, $request->email));
            return back()->with('success', 'Un email de réinitialisation a été envoyé à votre adresse.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de réinitialisation: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer.');
        }
    }

    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect('/connexion')->with('error', 'Lien de réinitialisation invalide.');
        }

        return view('auth.nouveau-mot-de-passe', compact('token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->with('error', 'Ce lien de réinitialisation n\'est pas valide.');
        }

        $createdAt = new \DateTime($resetRecord->created_at);
        $now = new \DateTime();
        $diff = $now->getTimestamp() - $createdAt->getTimestamp();

        if ($diff > 3600) { 
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->with('error', 'Ce lien de réinitialisation a expiré.');
        }

        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->with('error', 'Ce lien de réinitialisation n\'est pas valide.');
        }

        $user = DB::table('utilisateurs')
            ->where('Courriel', $request->email)
            ->first();

        if (!$user) {
            return back()->with('error', 'Aucun compte trouvé avec cette adresse email.');
        }

        DB::table('utilisateurs')
            ->where('Courriel', $request->email)
            ->update([
                'MotDePasse' => Hash::make($request->password),
            ]);

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('/connexion')->with('success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
    }
}

