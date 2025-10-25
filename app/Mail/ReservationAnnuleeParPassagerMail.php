<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationAnnuleeParPassagerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $trajet;
    public $passager;
    public $reservation;

    public function __construct($trajet, $passager, $reservation)
    {
        $this->trajet = $trajet;
        $this->passager = $passager;
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('Annulation de votre réservation - Covoiturage 2025')
                    ->view('emails.reservation-annulee-par-passager');
    }
}

