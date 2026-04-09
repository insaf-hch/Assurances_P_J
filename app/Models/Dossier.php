<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    protected $fillable = [
        'numero_dossier',
        'numero_jugement',
        'date_jugement',
        'date_accident',
        'nom_victime',
        'nom_assurance',
        'nom_assurance_normalise',
        'adresse_assurance',
        'nom_employeur',
        'type_cas',
        'montant_initial',
        'expertise',
        'fichier_pdf',
        'texte_ocr',
    ];

    protected function casts(): array
    {
        return [
            'date_jugement' => 'date',
            'date_accident' => 'date',
            'montant_initial' => 'decimal:2',
            'expertise' => 'decimal:2',
        ];
    }

    public function calcul()
    {
        return $this->hasOne(Calcul::class);
    }
}
