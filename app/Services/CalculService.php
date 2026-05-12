<?php

namespace App\Services;

use App\Models\Dossier;
use App\Models\Calcul;
use Illuminate\Support\Facades\Log;

class CalculService
{
    /**
     * Calcule les frais de justice selon le barème marocain (aligné sur dossier-calc.js)
     */
    public function calculerFraisJustice(float $montant): float
    {
        if ($montant <= 0)     return 0;
        if ($montant <= 5000)  return round($montant * 0.04, 2);          // 4%
        if ($montant <= 20000) return round($montant * 0.025, 2);         // 2.5%
        return                 round(($montant * 0.01) + 300, 2);         // 1% + 300
    }

    public static function typeMalafLabel(string $typeCas, ?string $typeMalaf): string
    {
        if ($typeCas === 'mortalite') return 'ملف وفاة';
        if ($typeCas === 'corporel') {
            return match ($typeMalaf) {
                'ras'    => 'رأس مال',
                'taawid' => 'تعويض',
                'mixte'  => 'مختلط',
                default  => 'غير محدد',
            };
        }
        return 'غير معروف';
    }

    /**
     * حساب المبلغ الأصلي الذي سيتم عرضه في الجدول (الواجهة)
     * هذا يطبق منطق:
     * - إذا وفاة إيراد عمري: مجموع المستفيدين × 10
     * - إذا وفاة رأس مال: مجموع المستفيدين
     * - إذا存在 مصاريف علاج أو تعويضات: المبلغ الأصلي + مصاريف العلاج + التعويضات
     * -否則: المبلغ الأصلي فقط
     */
    public function getMontantOriginalCalcule(Dossier $dossier): float
    {
        $typeCas = $dossier->type_cas;
            if ($typeCas === 'nizaat_shughl') {
        return round(
            ($dossier->nizaat_darar    ?? 0) +
            ($dossier->nizaat_ikhtar   ?? 0) +
            ($dossier->nizaat_otla     ?? 0) +
            ($dossier->nizaat_aqdamiya ?? 0),
            2
        );
    }
        $montantInitial = (float) ($dossier->montant_initial ?? 0);
        $rasemal = (float) ($dossier->montant_rasemal_ijmali ?? 0);
        $taawidat = (float) ($dossier->montant_taawidat_youmiya ?? 0);
        $beneficiaires = $dossier->beneficiaires_json;

        // 1. حالات الوفاة
        if ($typeCas === 'wafaya_irad_omri') {
            // كل مبلغ مستفيد يُضرب في 10
            return $this->sommeBeneficiaires($beneficiaires, true);
        }
        
        if ($typeCas === 'wafaya_ras_mal') {
            // مجموع مبالغ المستفيدين فقط
            return $this->sommeBeneficiaires($beneficiaires, false);
        }

        // 2. إذا كانت هناك مصاريف علاج أو تعويضات يومية
        if ($rasemal > 0 || $taawidat > 0) {
            return round($montantInitial + $rasemal + $taawidat, 2);
        }

        // 3. الحالة الافتراضية
        return $montantInitial;
    }

    /**
     * دالة مساعدة لحساب مجموع مبالغ المستفيدين من JSON
     */
    private function sommeBeneficiaires($beneficiairesJson, bool $foisDix = false): float
    {
        if (empty($beneficiairesJson)) {
            return 0;
        }
        
        // إذا كانت البيانات نصية JSON، نحولها إلى array
        $beneficiaires = is_array($beneficiairesJson) 
            ? $beneficiairesJson 
            : json_decode($beneficiairesJson, true);
        
        if (!is_array($beneficiaires) || count($beneficiaires) === 0) {
            return 0;
        }
        
        $sum = 0;
        foreach ($beneficiaires as $beneficiaire) {
            $montant = (float) ($beneficiaire['montant'] ?? 0);
            $sum += $foisDix ? $montant * 10 : $montant;
        }
        
        return round($sum, 2);
    }

   public function createOrUpdateCalcul(Dossier $dossier): Calcul
{
    if (is_null($dossier->montant_initial) && $dossier->type_cas !== 'nizaat_shughl') {
        throw new \InvalidArgumentException(
            "Le montant initial est manquant pour le dossier #{$dossier->id}"
        );
    }

    $montantBase   = $this->getMontantDeBase($dossier);
    $rasmQadai     = $this->calculerFraisJustice($montantBase);
    $rusumMurafaa  = 10.00;
    $rasmBahth     = $dossier->type_cas === 'gharama_ijbariya' ? 0.00 : 20.00;
    $expertise     = (float) ($dossier->expertise ?? 0);
    $masarifJanaza = (float) ($dossier->masarif_janaza ?? 0);

    $total = ceil($rasmQadai + $rusumMurafaa + $rasmBahth + $expertise + $masarifJanaza);

    $details = [
        'montant_original'  => (float) ($dossier->montant_initial ?? 0),
        'montant_rasemal'   => (float) ($dossier->montant_rasemal_ijmali ?? 0),
        'montant_taawidat'  => (float) ($dossier->montant_taawidat_youmiya ?? 0),
        'bareme_applique'   => $this->getBaremeDescription($montantBase),
        'formule'           => $this->getFormuleDetaillee($montantBase),
        'date_calcul'       => now()->toDateTimeString(),
    ];

    Log::info('Calcul effectué', [
        'dossier_id'   => $dossier->id,
        'montant_base' => $montantBase,
        'rasm_qadai'   => $rasmQadai,
        'total'        => $total,
    ]);

    return Calcul::updateOrCreate(
        ['dossier_id' => $dossier->id],
        [
            'montant_apres_cas'   => $montantBase,
            'rasm_qadai'          => $rasmQadai,
            'rusum_murafaa'       => $rusumMurafaa,
            'rasm_bahth'          => $rasmBahth,
            'expertise'           => $expertise,
            'masarif_janaza'      => $masarifJanaza,
            'total'               => $total,
            'total_en_lettres_ar' => $this->convertirEnLettres($total),
            'type_cas_applique'   => $dossier->type_cas,
            'details_calcul'      => $details,
        ]
    );
}

private function getMontantDeBase(Dossier $dossier): float
{
    $typeCas        = $dossier->type_cas;
    $montantInitial = (float) ($dossier->montant_initial ?? 0);
    $rasemal        = (float) ($dossier->montant_rasemal_ijmali ?? 0);
    $taawidat       = (float) ($dossier->montant_taawidat_youmiya ?? 0);
    $beneficiaires  = $dossier->beneficiaires_json;

    switch ($typeCas) {

        case 'irad_omri':
            return $montantInitial * 10;

        case 'irad_omri_ras_mal':
            return $montantInitial;

        case 'wafaya_irad_omri':
            return $this->sommeBeneficiaires($beneficiaires, true);

        case 'wafaya_ras_mal':
            return $this->sommeBeneficiaires($beneficiaires, false);

        case 'masdar_total_taawidat':
            if ($rasemal > 0 || $taawidat > 0) {
                return round($rasemal + $taawidat, 2);
            }
            return $montantInitial;

        case 'gharama_ijbariya':
            return $montantInitial;

        case 'nizaat_shughl':          // ← ici dans le switch
            return round(
                ($dossier->nizaat_darar    ?? 0) +
                ($dossier->nizaat_ikhtar   ?? 0) +
                ($dossier->nizaat_otla     ?? 0) +
                ($dossier->nizaat_aqdamiya ?? 0),
                2
            );

        case 'autre':
        default:
            if ($rasemal > 0 || $taawidat > 0) {
                return round($rasemal + $taawidat, 2);
            }
            return $montantInitial;
    }
}

    private function getBaremeDescription(float $montant): string
    {
        if ($montant <= 5000)  return "4% (0 - 5,000 DH)";
        if ($montant <= 20000) return "2.5% (5,001 - 20,000 DH)";
        return                 "1% + 300 DH (montant > 20,000 DH)";
    }

    private function getFormuleDetaillee(float $montant): string
    {
        if ($montant <= 5000)
            return "{$montant} × 4% = " . round($montant * 0.04, 2);
        if ($montant <= 20000)
            return "{$montant} × 2.5% = " . round($montant * 0.025, 2);
        return "({$montant} × 1%) + 300 = " . round(($montant * 0.01) + 300, 2);
    }

    private function convertirEnLettres(float $montant): string
    {
        $entier = (int) $montant;

        if ($entier < 0 || $entier > 999999) return 'مبلغ غير محدد';

        $units = ['', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة'];
        $tens  = ['', 'عشرة', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'];

        if ($entier < 10)  return $units[$entier] . ' دراهم';
        if ($entier < 100) {
            $dizaine = intdiv($entier, 10);
            $unite   = $entier % 10;
            if ($unite == 0) return $tens[$dizaine] . ' درهما';
            return $units[$unite] . ' و ' . $tens[$dizaine] . ' درهما';
        }
        return (string) $entier . ' درهماً';
    }

  public function buildBreakdown(Dossier $dossier): array
{
    $calcul = $dossier->calcul;
    if (!$calcul) return ['error' => 'Aucun calcul trouvé'];

    $detailsCalcul = is_string($calcul->details_calcul)
        ? json_decode($calcul->details_calcul, true)
        : ($calcul->details_calcul ?? []);

    $montantInitial  = (float) ($dossier->montant_initial ?? 0);
    $taawidat        = (float) ($dossier->montant_taawidat ?? 0);
    $masarifTibiya   = (float) ($dossier->montant_masarif_tibiya ?? 0);
    $masarifJanaza   = (float) ($calcul->masarif_janaza ?? 0);

    // ── المجموع حسب نوع الحالة ──
    if ($dossier->type_cas === 'nizaat_shughl') {
        $majmou = round(
            ($dossier->nizaat_darar    ?? 0) +
            ($dossier->nizaat_ikhtar   ?? 0) +
            ($dossier->nizaat_otla     ?? 0) +
            ($dossier->nizaat_aqdamiya ?? 0),
            2
        );
    } else {
        $majmou = round($montantInitial + $taawidat + $masarifTibiya + $masarifJanaza, 2);
    }

    return [
        'type_cas'               => $dossier->type_cas,
        'montant'                => $montantInitial,
        'montant_taawidat'       => $taawidat,
        'montant_masarif_tibiya' => $masarifTibiya,
        'masarif_janaza'         => $masarifJanaza,
        'montant_original'       => $majmou,
        'nizaat_darar'           => (float) ($dossier->nizaat_darar    ?? 0),
        'nizaat_ikhtar'          => (float) ($dossier->nizaat_ikhtar   ?? 0),
        'nizaat_otla'            => (float) ($dossier->nizaat_otla     ?? 0),
        'nizaat_aqdamiya'        => (float) ($dossier->nizaat_aqdamiya ?? 0),
        'rasm_qadai'             => (float) ($calcul->rasm_qadai    ?? 0),
        'rusum_murafaa'          => (float) ($calcul->rusum_murafaa ?? 0),
        'rasm_bahth'             => (float) ($calcul->rasm_bahth    ?? 0),
        'expertise'              => (float) ($calcul->expertise     ?? 0),
        'total'                  => (float) ($calcul->total         ?? 0),
        'total_lettres'          => $calcul->total_en_lettres_ar,
        'bareme'                 => $detailsCalcul['bareme_applique'] ?? 'N/A',
        'formule'                => $detailsCalcul['formule']         ?? 'N/A',
    ];
}
}   