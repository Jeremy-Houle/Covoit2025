<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrajetConfirmeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $trajet;
    public $passager;
    public $reservation;
    public $type; 

    public function __construct($trajet, $passager, $reservation, $type = 'confirmed')
    {
        $this->trajet = $trajet;
        $this->passager = $passager;
        $this->reservation = $reservation;
        $this->type = $type;
    }

    public function build()
    {
        $subject = $this->type === 'modified' 
            ? 'Modification de votre trajet - Covoiturage 2025' 
            : 'Confirmation de votre trajet - Covoiturage 2025';
            
        return $this->subject($subject)
                    ->view('emails.trajet-confirme');
    }
}

