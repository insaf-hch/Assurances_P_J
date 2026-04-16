<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'type_malaf',
        'montant_initial',
        'montant_rasemal_ijmali',
        'montant_taawidat_youmiya',
        'beneficiaires_json',
        'masarif_janaza',
        'expertise',
        'fichier_pdf',
        'texte_ocr',
        'saved',
        'bayan_id',
    ];

    protected function casts(): array
    {
        return [
            'date_jugement' => 'date',
            'date_accident' => 'date',
            'montant_initial' => 'decimal:2',
            'montant_rasemal_ijmali' => 'decimal:2',
            'montant_taawidat_youmiya' => 'decimal:2',
            'masarif_janaza' => 'decimal:2',
            'expertise' => 'decimal:2',
            'beneficiaires_json' => 'array',
            'saved' => 'boolean',
        ];
    }

    public function calcul(): HasOne
    {
        return $this->hasOne(Calcul::class);
    }

    public function bayan(): BelongsTo
    {
        return $this->belongsTo(Bayan::class);
    }
}
