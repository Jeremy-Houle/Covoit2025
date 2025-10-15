<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    protected $table = 'trajets';

    protected $fillable = [
        'IdConducteur',
        'Distance',
        'Depart',
        'Destination',
        'DateTrajet',
        'HeureTrajet',
        'PlacesDisponibles',
        'Prix',
        'TypeConversation',
        'Musique',
        'Fumeur',
    ];

}
