<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calcul extends Model
{
    //
    protected $fillable = [
    'dossier_id',
    'montant_apres_cas',
    'rasm_qadai',
    'rusum_murafaa',
    'rasm_bahth',
    'expertise',
    'total',
    'total_en_lettres_ar',
    'numero_amr_tanfidhi',
    'date_generation'
];

public function dossier()
{
    return $this->belongsTo(Dossier::class);
}
}
