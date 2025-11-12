<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'Evaluation';
    protected $primaryKey = 'IdEvaluation';
    public $timestamps = false;

    protected $fillable = [
        'IdUtilisateur',
        'Note',
        'IdTrajet',
    ];
}
