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
        'masarif_janaza',
        'total',
        'total_en_lettres_ar',
        'numero_amr_tanfidhi',
        'date_generation',
    ];

    protected function casts(): array
    {
        return [
            'montant_apres_cas' => 'decimal:2',
            'rasm_qadai' => 'decimal:2',
            'rusum_murafaa' => 'decimal:2',
            'rasm_bahth' => 'decimal:2',
            'expertise' => 'decimal:2',
            'masarif_janaza' => 'decimal:2',
            'total' => 'decimal:2',
            'date_generation' => 'date',
        ];
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }
}
