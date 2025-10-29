<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LesMessage extends Model
{
    protected $table = 'LesMessages'; 

    protected $primaryKey = 'IdMessage';

    public $timestamps = false; 

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

