<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    protected $table = 'trajets';
    protected $primaryKey = 'IdTrajet';
    public $timestamps = false; 

    protected $fillable = [
        'IdConducteur',
        'NomConducteur',
        'Distance',
        'Depart',
        'Destination',
        'DateTrajet',
        'HeureTrajet',
        'PlacesDisponibles',
        'Prix',
        'AnimauxAcceptes',
        'TypeConversation',
        'Musique',
        'Fumeur',
        'RappelEmail',
        'RappelEnvoye',
    ];

}
