<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InfoCompteModifieMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $changedFields;

    public function __construct($user, $changedFields)
    {
        $this->user = $user;
        $this->changedFields = $changedFields;
    }

    public function build()
    {
        return $this->subject('Modification de vos informations de compte')
                    ->view('emails.info-compte-modifie');
    }
}

