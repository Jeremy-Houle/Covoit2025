<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EnvoyerRappelsTrajets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trajets:envoyer-rappels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des rappels par email aux conducteurs 2 heures avant leur trajet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Vérification des trajets nécessitant un rappel...');
        
        $now = Carbon::now();
        $deuxHeuresPlus = Carbon::now()->addHours(2);
        
        $trajets = DB::table('trajets as t')
            ->join('utilisateurs as u', 't.IdConducteur', '=', 'u.IdUtilisateur')
            ->select('t.*', 'u.Nom as ConducteurNom', 'u.Prenom as ConducteurPrenom', 'u.Courriel as ConducteurEmail')
            ->where('t.RappelEmail', true)
            ->where('t.RappelEnvoye', false)
            ->whereDate('t.DateTrajet', $now->toDateString())
            ->get();
        
        $rappelsEnvoyes = 0;
        
        foreach ($trajets as $trajet) {
            $dateTimeTrajet = Carbon::parse($trajet->DateTrajet . ' ' . $trajet->HeureTrajet);
            
            $diffEnMinutes = $now->diffInMinutes($dateTimeTrajet, false);
            
            if ($diffEnMinutes >= 105 && $diffEnMinutes <= 135) {
                try {
                    $data = [
                        'conducteurNom' => $trajet->ConducteurPrenom . ' ' . $trajet->ConducteurNom,
                        'depart' => $trajet->Depart,
                        'destination' => $trajet->Destination,
                        'dateTrajet' => Carbon::parse($trajet->DateTrajet)->format('d/m/Y'),
                        'heureTrajet' => Carbon::parse($trajet->HeureTrajet)->format('H:i'),
                        'placesDisponibles' => $trajet->PlacesDisponibles,
                    ];
                    
                    $heuresRestantes = round(Carbon::now()->diffInHours(Carbon::parse($trajet->DateTrajet . ' ' . $trajet->HeureTrajet)));
                    
                    Mail::send('emails.rappel-trajet', $data, function($message) use ($trajet, $heuresRestantes) {
                        $message->to($trajet->ConducteurEmail)
                                ->subject('🚗 Rappel : Votre trajet dans ' . $heuresRestantes . 'h - Covoit2025');
                    });
                    
                    DB::table('trajets')
                        ->where('IdTrajet', $trajet->IdTrajet)
                        ->update(['RappelEnvoye' => true]);
                    
                    $rappelsEnvoyes++;
                    $this->info("✓ Rappel envoyé à {$trajet->ConducteurEmail} pour le trajet #{$trajet->IdTrajet}");
                    
                } catch (\Exception $e) {
                    $this->error("✗ Erreur lors de l'envoi du rappel pour le trajet #{$trajet->IdTrajet}: " . $e->getMessage());
                }
            }
        }
        
        if ($rappelsEnvoyes > 0) {
            $this->info("\n✅ Total : {$rappelsEnvoyes} rappel(s) envoyé(s) avec succès!");
        } else {
            $this->info("\nℹ️ Aucun rappel à envoyer pour le moment.");
        }
        
        return Command::SUCCESS;
    }
}
