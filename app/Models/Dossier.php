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
        'montant_taawidat',        // ← ajouté
        'montant_masarif_tibiya',  // ← ajouté
        'beneficiaires_json',
        'masarif_janaza',
        'expertise',
        'fichier_pdf',
        'texte_ocr',
        'saved',
        'bayan_id',
        'nizaat_darar',            // ← ajouté
        'nizaat_ikhtar',           // ← ajouté
        'nizaat_otla',             // ← ajouté
        'nizaat_aqdamiya',         // ← ajouté
    ];

    protected function casts(): array
    {
        return [
            'date_jugement'            => 'date',
            'date_accident'            => 'date',
            'montant_initial'          => 'decimal:2',
            'montant_rasemal_ijmali'   => 'decimal:2',
            'montant_taawidat_youmiya' => 'decimal:2',
            'montant_taawidat'         => 'decimal:2',  // ← ajouté
            'montant_masarif_tibiya'   => 'decimal:2',  // ← ajouté
            'masarif_janaza'           => 'decimal:2',
            'expertise'                => 'decimal:2',
            'beneficiaires_json'       => 'array',
            'saved'                    => 'boolean',
            'nizaat_darar'             => 'decimal:2',  // ← ajouté
            'nizaat_ikhtar'            => 'decimal:2',  // ← ajouté
            'nizaat_otla'              => 'decimal:2',  // ← ajouté
            'nizaat_aqdamiya'          => 'decimal:2',  // ← ajouté
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