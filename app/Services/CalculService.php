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

    $total = (float) ceil($rasmQadai + $rusumMurafaa + $rasmBahth + $expertise);

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
    return round($this->sommeBeneficiaires($beneficiaires, true) + (float)($dossier->masarif_janaza ?? 0), 2);

case 'wafaya_ras_mal':
    return round($this->sommeBeneficiaires($beneficiaires, false) + (float)($dossier->masarif_janaza ?? 0), 2);;

   case 'masdar_total_taawidat':
    $masarifTibiya   = (float) ($dossier->montant_masarif_tibiya ?? 0);
    $taawidatMontant = (float) ($dossier->montant_taawidat ?? 0);
    return round($montantInitial + $taawidatMontant + $masarifTibiya, 2);
    // ✅ 39336.13 + 4121.66 + 0 = 43457.79
    // 39336.13 + 4121.66 + 0 = 43457.79 ✅

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
    $entier = (int) $montant; // ceil() déjà appliqué avant l'appel

    if ($entier <= 0 || $entier > 999999) return 'مبلغ غير محدد';

    $units = [
        1=>'واحد', 2=>'اثنان', 3=>'ثلاثة', 4=>'أربعة', 5=>'خمسة',
        6=>'ستة', 7=>'سبعة', 8=>'ثمانية', 9=>'تسعة', 10=>'عشرة',
        11=>'أحد عشر', 12=>'اثنا عشر', 13=>'ثلاثة عشر', 14=>'أربعة عشر',
        15=>'خمسة عشر', 16=>'ستة عشر', 17=>'سبعة عشر', 18=>'ثمانية عشر',
        19=>'تسعة عشر',
    ];
    $tens = [
        2=>'عشرون', 3=>'ثلاثون', 4=>'أربعون', 5=>'خمسون',
        6=>'ستون', 7=>'سبعون', 8=>'ثمانون', 9=>'تسعون',
    ];
    $hundreds = [
        1=>'مائة', 2=>'مئتان', 3=>'ثلاثمائة', 4=>'أربعمائة',
        5=>'خمسمائة', 6=>'ستمائة', 7=>'سبعمائة', 8=>'ثمانمائة', 9=>'تسعمائة',
    ];

    $parts = [];

    // الآلاف
    $milliers = intdiv($entier, 1000);
    $reste    = $entier % 1000;

    if ($milliers > 0) {
        if ($milliers === 1)     $parts[] = 'ألف';
        elseif ($milliers === 2) $parts[] = 'ألفان';
        elseif ($milliers <= 10) $parts[] = ($units[$milliers] ?? $milliers) . ' آلاف';
        else                     $parts[] = ($units[$milliers] ?? $milliers) . ' ألف';
    }

    // المئات
    $centaines = intdiv($reste, 100);
    $reste2    = $reste % 100;
    if ($centaines > 0) $parts[] = $hundreds[$centaines];

    // العشرات والآحاد
    if ($reste2 > 0) {
        if ($reste2 <= 19) {
            $parts[] = $units[$reste2];
        } else {
            $dizaine = intdiv($reste2, 10);
            $unite   = $reste2 % 10;
            if ($unite > 0)
                $parts[] = ($units[$unite] ?? '') . ' و' . $tens[$dizaine];
            else
                $parts[] = $tens[$dizaine];
        }
    }

    return implode(' و', $parts) . ' درهماً';
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
   } elseif ($dossier->type_cas === 'wafaya_irad_omri' || $dossier->type_cas === 'wafaya_ras_mal') {
    $foisDix  = ($dossier->type_cas === 'wafaya_irad_omri');
    $montantInitial = $this->sommeBeneficiaires($dossier->beneficiaires_json, $foisDix);
    $majmou   = round($montantInitial + $masarifJanaza, 2);
} else {
    $montantInitial = (float) ($dossier->montant_initial ?? 0);
    
    if ($dossier->type_cas === 'masdar_total_taawidat') {
        // ✅ montant déjà complet, pas de double comptage
        $majmou = round($montantInitial + $taawidat + $masarifTibiya, 2);
        // 39336.13 + 4121.66 + 0 = 43457.79 ✅
    } else {
        $majmou = round($montantInitial + $taawidat + $masarifTibiya + $masarifJanaza, 2);
    }
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