<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table = 'commentaires';
    protected $primaryKey = 'IdCommentaire';
    public $timestamps = false;

    protected $fillable = [
        'IdUtilisateur',
        'Commentaire',
        'IdTrajet',
        'DateCommentaire'
    ];

    protected $casts = [
        'DateCommentaire' => 'datetime',
    ];

}
