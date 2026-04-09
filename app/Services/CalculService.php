<?php

namespace App\Services;

use App\Models\Calcul;
use App\Models\Dossier;

/**
 * Cas métier + الرسم القضائي + total final.
 */
class CalculService
{
    /**
     * Cas 1 : montant × 10 — Cas 2,3 : direct — Cas 4 : réservé (pas de transformation).
     */
    public function montantApresCas(string $typeCas, float $montantInitial): float
    {
        return match ($typeCas) {
            'irad_omri' => round($montantInitial * 10, 2),
            'irad_omri_ras_mal', 'gharama_ijbariya', 'autre' => round($montantInitial, 2),
            default => round($montantInitial, 2),
        };
    }

    /**
     * Barème الرسم القضائي appliqué sur la base après cas.
     */
    public function rasmQadai(float $montantBase): float
    {
        if ($montantBase <= 5000) {
            return round($montantBase * 0.04, 2);
        }
        if ($montantBase <= 20000) {
            return round($montantBase * 0.025, 2);
        }

        return round($montantBase * 0.01 + 300, 2);
    }

    /**
     * total = rasm_qadai + 10 + 20 + expertise
     */
    public function totalFinal(float $rasmQadai, float $expertise): float
    {
        $rusumMurafaa = 10.0;
        $rasmBahth = 20.0;

        return round($rasmQadai + $rusumMurafaa + $rasmBahth + $expertise, 2);
    }

    public function createOrUpdateCalcul(Dossier $dossier, string $typeCas, float $expertise): Calcul
    {
        $montantInitial = (float) $dossier->montant_initial;
        $montantApres = $this->montantApresCas($typeCas, $montantInitial);
        $rasm = $this->rasmQadai($montantApres);
        $total = $this->totalFinal($rasm, $expertise);

        return Calcul::updateOrCreate(
            ['dossier_id' => $dossier->id],
            [
                'montant_apres_cas' => $montantApres,
                'rasm_qadai' => $rasm,
                'rusum_murafaa' => 10,
                'rasm_bahth' => 20,
                'expertise' => $expertise,
                'total' => $total,
                'date_generation' => now()->toDateString(),
            ]
        );
    }
}
