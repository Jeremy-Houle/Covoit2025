<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LesMessage extends Model
{
    protected $table = 'LesMessages'; // nom exact de la table

    protected $primaryKey = 'IdMessage';

    public $timestamps = false; // car vous avez un champ DateEnvoi manuel

    protected $fillable = [
        'IdExpediteur',
        'IdDestinataire',
        'LeMessage',
        'DateEnvoi',
    ];

    public function expediteur()
    {
        return $this->belongsTo(User::class, 'IdExpediteur', 'id');
    }

    public function destinataire()
    {
        return $this->belongsTo(User::class, 'IdDestinataire', 'id');
    }
}

