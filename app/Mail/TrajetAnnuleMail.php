<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrajetAnnuleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $trajet;
    public $passager;
    public $conducteur;
    public $type; 

    public function __construct($trajet, $passager, $conducteur, $type = 'cancelled')
    {
        $this->trajet = $trajet;
        $this->passager = $passager;
        $this->conducteur = $conducteur;
        $this->type = $type;
    }

    public function build()
    {
        $subject = $this->type === 'cancelled' 
            ? 'Annulation de trajet - Covoiturage 2025' 
            : 'Modification de trajet - Covoiturage 2025';
            
        return $this->subject($subject)
                    ->view('emails.trajet-annule');
    }
}

