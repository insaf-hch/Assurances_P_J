<?php

namespace App\Services;

use App\Models\Calcul;
use App\Models\Dossier;

/**
 * Calcul automatique : cas 1 à 5 + الرسم القضائي + الإجمالي.
 * Même logique que le JS (voir public/js/dossier-calc.js).
 */
class CalculService
{
    public const TYPE_LABELS = [
        'irad_omri' => 'إيراد عمري',
        'irad_omri_ras_mal' => 'إيراد عمري محول إلى رأس مال',
        'masdar_total_taawidat' => 'إيراد محول لرأسمال إجمالي + تعويضات يومية',
        'gharama_ijbariya' => 'غرامة إجبارية',
        'wafaya_irad_omri' => 'وفاة — إيراد عمري (مستفيدون)',
        'wafaya_ras_mal' => 'وفاة — محول لرأس مال (مستفيدون)',
        'autre' => 'أخرى',
    ];

    /** Montant servant de base au barème du الرسم القضائي */
    public function montantPourRasm(Dossier $dossier): float
    {
        $type = $dossier->type_cas ?? 'autre';

        return match ($type) {
            'irad_omri' => round((float) $dossier->montant_initial * 10, 2),
            'irad_omri_ras_mal' => round((float) $dossier->montant_initial, 2),
            'masdar_total_taawidat' => round(
                (float) $dossier->montant_rasemal_ijmali + (float) $dossier->montant_taawidat_youmiya,
                2
            ),
            'gharama_ijbariya' => round((float) $dossier->montant_initial, 2),
            'wafaya_irad_omri' => $this->sommeBeneficiaires($dossier->beneficiaires_json, true),
            'wafaya_ras_mal' => $this->sommeBeneficiaires($dossier->beneficiaires_json, false),
            default => round((float) $dossier->montant_initial, 2),
        };
    }

    /**
     * @param  array<int, array{montant?: float|int|string}>|null  $list
     */
    public function sommeBeneficiaires(?array $list, bool $foisDix): float
    {
        if (! is_array($list) || $list === []) {
            return 0.0;
        }

        $sum = 0.0;
        foreach ($list as $row) {
            if (! is_array($row)) {
                continue;
            }
            $m = (float) ($row['montant'] ?? 0);
            $sum += $foisDix ? $m * 10 : $m;
        }

        return round($sum, 2);
    }

    public function rasmQadai(float $montant): float
    {
        if ($montant <= 0) {
            return 0.0;
        }
        if ($montant <= 5000) {
            return round($montant * 0.04, 2);
        }
        if ($montant <= 20000) {
            return round($montant * 0.025, 2);
        }

        return round($montant * 0.01 + 300, 2);
    }

    /**
     * Cas 4 : sans رسم البحث (20). Cas 5 : + مصاريف الجنازة.
     *
     * @return array{
     *     montant_pour_rasm: float,
     *     montant_affiche_original: float,
     *     rasm_qadai: float,
     *     rusum_murafaa: float,
     *     rasm_bahth: float,
     *     expertise: float,
     *     masarif_janaza: float,
     *     total: float,
     *     type_cas: string|null
     * }
     */
    public function buildBreakdown(Dossier $dossier): array
    {
        $type = $dossier->type_cas ?? 'autre';
        $montantPourRasm = $this->montantPourRasm($dossier);
        $rasm = $this->rasmQadai($montantPourRasm);
        $expertise = (float) $dossier->expertise;
        $rusumMurafaa = 10.0;
        $rasmBahth = $type === 'gharama_ijbariya' ? 0.0 : 20.0;
        $janaza = in_array($type, ['wafaya_irad_omri', 'wafaya_ras_mal'], true)
            ? (float) $dossier->masarif_janaza
            : 0.0;

        $total = round($rasm + $rusumMurafaa + $rasmBahth + $expertise + $janaza, 2);

        $montantAfficheOriginal = match ($type) {
            'masdar_total_taawidat' => round(
                (float) $dossier->montant_rasemal_ijmali + (float) $dossier->montant_taawidat_youmiya,
                2
            ),
            'wafaya_irad_omri', 'wafaya_ras_mal' => $montantPourRasm,
            default => round((float) $dossier->montant_initial, 2),
        };

        return [
            'montant_pour_rasm' => $montantPourRasm,
            'montant_affiche_original' => $montantAfficheOriginal,
            'rasm_qadai' => $rasm,
            'rusum_murafaa' => $rusumMurafaa,
            'rasm_bahth' => $rasmBahth,
            'expertise' => $expertise,
            'masarif_janaza' => $janaza,
            'total' => $total,
            'type_cas' => $type,
        ];
    }

    public function createOrUpdateCalcul(Dossier $dossier): Calcul
    {
        $b = $this->buildBreakdown($dossier);

        return Calcul::updateOrCreate(
            ['dossier_id' => $dossier->id],
            [
                'montant_apres_cas' => $b['montant_pour_rasm'],
                'rasm_qadai' => $b['rasm_qadai'],
                'rusum_murafaa' => $b['rusum_murafaa'],
                'rasm_bahth' => $b['rasm_bahth'],
                'expertise' => $b['expertise'],
                'masarif_janaza' => $b['masarif_janaza'],
                'total' => $b['total'],
                'date_generation' => now()->toDateString(),
            ]
        );
    }

    public static function typeMalafLabel(?string $typeCas, ?string $typeMalaf): string
    {
        if (is_string($typeMalaf) && trim($typeMalaf) !== '') {
            return trim($typeMalaf);
        }

        if ($typeCas && isset(self::TYPE_LABELS[$typeCas])) {
            return self::TYPE_LABELS[$typeCas];
        }

        return '—';
    }
}
