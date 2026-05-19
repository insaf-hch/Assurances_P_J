<?php

namespace App\Services;

use App\Models\Dossier;
use App\Models\Calcul;
use Illuminate\Support\Facades\Log;

class CalculService
{
    public function calculerFraisJustice(float $montant): float
    {
        if ($montant <= 0)     return 0;
        if ($montant <= 5000)  return round($montant * 0.04, 2);
        if ($montant <= 20000) return round($montant * 0.025, 2);
        return                 round(($montant * 0.01) + 300, 2);
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

    public function getMontantOriginalCalcule(Dossier $dossier): float
    {
        $typeCas = $dossier->type_cas;
        $mi      = (float) ($dossier->montant_initial ?? 0);
        $mri     = (float) ($dossier->montant_rasemal_ijmali ?? 0);
        $mty     = (float) ($dossier->montant_taawidat_youmiya ?? 0);
        $taa     = (float) ($dossier->montant_taawidat ?? 0);
        $tib     = (float) ($dossier->montant_masarif_tibiya ?? 0);

        switch ($typeCas) {

            case 'nizaat_shughl':
                return round(
                    ($dossier->nizaat_darar    ?? 0) +
                    ($dossier->nizaat_ikhtar   ?? 0) +
                    ($dossier->nizaat_otla     ?? 0) +
                    ($dossier->nizaat_aqdamiya ?? 0),
                    2
                );

            case 'irad_omri':
                $tib = (float) ($dossier->montant_masarif_tibiya ?? 0);
                $tyd = (float) ($dossier->montant_taawidat_youmiya ?? 0);
                $taa = (float) ($dossier->montant_taawidat ?? 0);
                return round(($mi * 10) + $tib + $tyd + $taa, 2);

            case 'irad_omri_ras_mal':
                $tib = (float) ($dossier->montant_masarif_tibiya ?? 0);
                $tyd = (float) ($dossier->montant_taawidat_youmiya ?? 0);
                $taa = (float) ($dossier->montant_taawidat ?? 0);
                return round($mi + $tib + $tyd + $taa, 2);

            case 'masdar_total_taawidat':
                return $mi;

            case 'wafaya_irad_omri':
                return $this->sommeBeneficiaires($dossier->beneficiaires_json, true);

            case 'wafaya_ras_mal':
                return $this->sommeBeneficiaires($dossier->beneficiaires_json, false);

            case 'gharama_ijbariya':
                return $mi;

            default:
                if ($mri > 0 || $mty > 0) {
                    return round($mri + $mty + $taa + $tib, 2);
                }
                return round($mi + $taa + $tib, 2);
        }
    }

    private function sommeBeneficiaires($beneficiairesJson, bool $foisDix = false): float
    {
        if (empty($beneficiairesJson)) return 0;

        $beneficiaires = is_array($beneficiairesJson)
            ? $beneficiairesJson
            : json_decode($beneficiairesJson, true);

        if (!is_array($beneficiaires) || count($beneficiaires) === 0) return 0;

        $sum = 0;
        foreach ($beneficiaires as $beneficiaire) {
            $montant = (float) ($beneficiaire['montant'] ?? 0);
            $sum += $foisDix ? $montant * 10 : $montant;
        }
        return round($sum, 2);
    }

    public function createOrUpdateCalcul(Dossier $dossier): Calcul
    {
        if (empty($dossier->type_cas)) {
            throw new \InvalidArgumentException(
                "يجب اختيار نوع الحالة للملف #{$dossier->id}"
            );
        }

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
            'montant_original' => (float) ($dossier->montant_initial ?? 0),
            'montant_rasemal'  => (float) ($dossier->montant_rasemal_ijmali ?? 0),
            'montant_taawidat' => (float) ($dossier->montant_taawidat_youmiya ?? 0),
            'bareme_applique'  => $this->getBaremeDescription($montantBase),
            'formule'          => $this->getFormuleDetaillee($montantBase),
            'date_calcul'      => now()->toDateTimeString(),
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
        if (empty($dossier->type_cas)) return 0;

        $typeCas         = $dossier->type_cas;
        $montantInitial  = (float) ($dossier->montant_initial ?? 0);
        $taawidatYoumiya = (float) ($dossier->montant_taawidat_youmiya ?? 0);
        $beneficiaires   = $dossier->beneficiaires_json;

        switch ($typeCas) {

            case 'irad_omri':
                $tib = (float) ($dossier->montant_masarif_tibiya ?? 0);
                $tyd = (float) ($dossier->montant_taawidat_youmiya ?? 0);
                $taa = (float) ($dossier->montant_taawidat ?? 0);
                return round(($montantInitial * 10) + $tib + $tyd + $taa, 2);

            case 'irad_omri_ras_mal':
                $tib = (float) ($dossier->montant_masarif_tibiya ?? 0);
                $tyd = (float) ($dossier->montant_taawidat_youmiya ?? 0);
                $taa = (float) ($dossier->montant_taawidat ?? 0);
                return round($montantInitial + $tib + $tyd + $taa, 2);

            case 'wafaya_irad_omri':
                return round(
                    $this->sommeBeneficiaires($beneficiaires, true)
                    + (float) ($dossier->masarif_janaza ?? 0),
                    2
                );

            case 'wafaya_ras_mal':
                return round(
                    $this->sommeBeneficiaires($beneficiaires, false)
                    + (float) ($dossier->masarif_janaza ?? 0),
                    2
                );

            case 'masdar_total_taawidat':
                return round(
                    $montantInitial
                    + (float) ($dossier->montant_taawidat ?? 0)
                    + (float) ($dossier->montant_masarif_tibiya ?? 0)
                    + $taawidatYoumiya,
                    2
                );

            case 'gharama_ijbariya':
                return $montantInitial;

            case 'nizaat_shughl':
                return round(
                    ($dossier->nizaat_darar    ?? 0) +
                    ($dossier->nizaat_ikhtar   ?? 0) +
                    ($dossier->nizaat_otla     ?? 0) +
                    ($dossier->nizaat_aqdamiya ?? 0),
                    2
                );

            case 'autre':
            default:
                $taa = (float) ($dossier->montant_taawidat ?? 0);
                $tib = (float) ($dossier->montant_masarif_tibiya ?? 0);
                $mty = (float) ($dossier->montant_taawidat_youmiya ?? 0);
                $mri = (float) ($dossier->montant_rasemal_ijmali ?? 0);
                if ($mri > 0) {
                    return round($mri + $mty + $taa + $tib, 2);
                }
                return round($montantInitial + $taa + $mty + $tib, 2);
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

        $parts    = [];
        $milliers = intdiv($entier, 1000);
        $reste    = $entier % 1000;

        if ($milliers > 0) {
            if ($milliers === 1)     $parts[] = 'ألف';
            elseif ($milliers === 2) $parts[] = 'ألفان';
            elseif ($milliers <= 10) $parts[] = ($units[$milliers] ?? $milliers) . ' آلاف';
            else                     $parts[] = ($units[$milliers] ?? $milliers) . ' ألف';
        }

        $centaines = intdiv($reste, 100);
        $reste2    = $reste % 100;
        if ($centaines > 0) $parts[] = $hundreds[$centaines];

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
        $taawidatYoumiya = (float) ($dossier->montant_taawidat_youmiya ?? 0);

        // ── المجموع حسب نوع الحالة ──
        if ($dossier->type_cas === 'nizaat_shughl') {
            $majmou = round(
                ($dossier->nizaat_darar    ?? 0) +
                ($dossier->nizaat_ikhtar   ?? 0) +
                ($dossier->nizaat_otla     ?? 0) +
                ($dossier->nizaat_aqdamiya ?? 0),
                2
            );

        } elseif ($dossier->type_cas === 'irad_omri') {
            $majmou = round(($montantInitial * 10) + $taawidat + $masarifTibiya + $taawidatYoumiya, 2);

        } elseif ($dossier->type_cas === 'irad_omri_ras_mal') {
            $majmou = round($montantInitial + $taawidat + $masarifTibiya + $taawidatYoumiya, 2);

        } elseif ($dossier->type_cas === 'wafaya_irad_omri' || $dossier->type_cas === 'wafaya_ras_mal') {
            $foisDix        = ($dossier->type_cas === 'wafaya_irad_omri');
            $montantInitial = $this->sommeBeneficiaires($dossier->beneficiaires_json, $foisDix);
            $majmou         = round($montantInitial + $masarifJanaza, 2);

        } elseif ($dossier->type_cas === 'masdar_total_taawidat') {
            $majmou = round($montantInitial + $taawidat + $masarifTibiya + $taawidatYoumiya, 2);

        } else {
            $mri2 = (float) ($dossier->montant_rasemal_ijmali ?? 0);
            if ($mri2 > 0) {
                $majmou = round($mri2 + $taawidatYoumiya + $taawidat + $masarifTibiya, 2);
            } else {
                $majmou = round($montantInitial + $taawidat + $taawidatYoumiya + $masarifTibiya, 2);
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