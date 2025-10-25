<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationPaiementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $paiement;
    public $trajet;
    public $utilisateur;

    public function __construct($paiement, $trajet, $utilisateur)
    {
        $this->paiement = $paiement;
        $this->trajet = $trajet;
        $this->utilisateur = $utilisateur;
    }

    public function build()
    {
        return $this->subject('Confirmation de paiement - Covoiturage 2025')
                    ->view('emails.confirmation-paiement');
    }
}

