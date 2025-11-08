<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    protected $table = 'favoris';
    protected $primaryKey = 'IdFavori';
    public $timestamps = false;

    protected $fillable = [
        'IdUtilisateur',
        'IdTrajet',
        'DateAjout',
    ];

    protected $casts = [
        'DateAjout' => 'datetime',
    ];

    public function trajet()
    {
        return $this->belongsTo(Trajet::class, 'IdTrajet', 'IdTrajet');
    }
}

