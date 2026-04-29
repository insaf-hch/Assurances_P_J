<?php

namespace App\Services;

use App\Models\Dossier;
use App\Models\Calcul;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalculService
{
    /**
     * Calcule les frais de justice selon le barème marocain
     */
    public function calculerFraisJustice(float $montant): float
    {
        if ($montant > 200000) {
            $frais = ($montant * 0.01) + 300;
        } elseif ($montant > 40000) {
            $frais = $montant * 0.01;
        } elseif ($montant > 20000) {
            $frais = $montant * 0.02;
        } else {
            $frais = $montant * 0.03;
        }
        
        return round($frais, 2);
    }

    public static function typeMalafLabel($typeCas, $typeMalaf): string
{
    if ($typeCas === 'mortalite') {
        return 'ملف وفاة';
    }

    if ($typeCas === 'corporel') {
        return match ($typeMalaf) {
            'ras' => 'رأس مال',
            'taawid' => 'تعويض',
            'mixte' => 'مختلط',
            default => 'غير محدد',
        };
    }

    return 'غير معروف';
}
    
    /**
     * Crée ou met à jour un calcul pour un dossier
     */
    public function createOrUpdateCalcul(Dossier $dossier): Calcul
    {
        // Déterminer le montant de base selon le type de cas
        $montantBase = $this->getMontantDeBase($dossier);
        
        // Calculer les frais
        $rasmQadai = $this->calculerFraisJustice($montantBase);
        $rusumMurafaa = 10.00;  // Droits plaidoirie
        $rasmBahth = 20.00;      // Frais recherche
        
        // Total
        $total = $rasmQadai + $rusumMurafaa + $rasmBahth + ($dossier->expertise ?? 0) + ($dossier->masarif_janaza ?? 0);
        
        // Arrondi supérieur comme au Maroc
        $total = ceil($total);
        
        $details = [
            'montant_original' => $dossier->montant_initial,
            'montant_rasemal' => $dossier->montant_rasemal_ijmali,
            'montant_taawidat' => $dossier->montant_taawidat_youmiya,
            'bareme_applique' => $this->getBaremeDescription($montantBase),
            'formule' => $this->getFormuleDetaillee($montantBase),
            'date_calcul' => now()->toDateTimeString(),
        ];
        
        Log::info('Calcul effectué', [
            'dossier_id' => $dossier->id,
            'montant_base' => $montantBase,
            'rasm_qadai' => $rasmQadai,
            'total' => $total,
            'details' => $details
        ]);
        
        return Calcul::updateOrCreate(
            ['dossier_id' => $dossier->id],
            [
                'montant_apres_cas' => $montantBase,
                'rasm_qadai' => $rasmQadai,
                'rusum_murafaa' => $rusumMurafaa,
                'rasm_bahth' => $rasmBahth,
                'expertise' => $dossier->expertise ?? 0,
                'masarif_janaza' => $dossier->masarif_janaza ?? 0,
                'total' => $total,
                'total_en_lettres_ar' => $this->convertirEnLettres($total),
                'type_cas_applique' => $dossier->type_cas,
                'details_calcul' => $details,
            ]
        );
    }
    
    /**
     * Détermine le montant de base selon le type de cas
     */
    private function getMontantDeBase(Dossier $dossier): float
    {
        $typeCas = $dossier->type_cas;
        $montantInitial = (float) ($dossier->montant_initial ?? 0);
        
        // Cas 1: irad_omri, irad_omri_ras_mal, wafaya_irad_omri, wafaya_ras_mal
        if (in_array($typeCas, ['irad_omri', 'irad_omri_ras_mal', 'wafaya_irad_omri', 'wafaya_ras_mal'])) {
            return $montantInitial * 10;
        }
        
        // Cas 2 & 3: masdar_total_taawidat, gharama_ijbariya, autre
        return $montantInitial;
    }
    
    /**
     * Description du barème appliqué
     */
    private function getBaremeDescription(float $montant): string
    {
        if ($montant > 200000) {
            return "1% + 300 DH (montant > 200,000 DH)";
        } elseif ($montant > 40000) {
            return "1% (40,001 - 200,000 DH)";
        } elseif ($montant > 20000) {
            return "2% (20,001 - 40,000 DH)";
        } else {
            return "3% (0 - 20,000 DH)";
        }
    }
    
    /**
     * Formule détaillée du calcul
     */
    private function getFormuleDetaillee(float $montant): string
    {
        if ($montant > 200000) {
            return "({$montant} × 1%) + 300 = " . round(($montant * 0.01) + 300, 2);
        } elseif ($montant > 40000) {
            return "{$montant} × 1% = " . round($montant * 0.01, 2);
        } elseif ($montant > 20000) {
            return "{$montant} × 2% = " . round($montant * 0.02, 2);
        } else {
            return "{$montant} × 3% = " . round($montant * 0.03, 2);
        }
    }
    
    /**
     * Convertit un nombre en lettres (arabe)
     */
    private function convertirEnLettres(float $montant): string
    {
        $entier = (int) $montant;
        
        if ($entier < 0 || $entier > 999999) {
            return 'مبلغ غير محدد';
        }
        
        // Mapping des nombres en arabe
        $units = ['', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة'];
        $tens = ['', 'عشرة', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'];
        $hundreds = ['', 'مائة', 'مائتان', 'ثلاثمائة', 'أربعمائة', 'خمسمائة', 'ستمائة', 'سبعمائة', 'ثمانمائة', 'تسعمائة'];
        
        // Simplifié pour l'exemple
        if ($entier < 100) {
            if ($entier < 10) {
                return $units[$entier] . ' دراهم';
            }
           $dizaine = intdiv($entier, 10);
        $unite = $entier % 10;

        if ($unite == 0) {
            return $tens[$dizaine] . ' درهما';
        }

        return $units[$unite] . ' و ' . $tens[$dizaine] . ' درهما';
                }
        return (string) $entier . ' درهماً';
    }
    
    /**
     * Construit le détail du calcul pour l'affichage
     */
    public function buildBreakdown(Dossier $dossier): array
    {
        $calcul = $dossier->calcul;
        
        if (!$calcul) {
            return ['error' => 'Aucun calcul trouvé'];
        }
        
        return [
            'montant_original' => $dossier->montant_initial,
            'type_cas' => $dossier->type_cas,
            'montant_apres_cas' => $calcul->montant_apres_cas,
            'details' => [
                'الرسوم القضائية' => $calcul->rasm_qadai,
                'حقوق المرافعة' => $calcul->rusum_murafaa,
                'رسم البحث' => $calcul->rasm_bahth,
                'الخبرة' => $calcul->expertise,
                'مصاريف الجنازة' => $calcul->masarif_janaza,
            ],
            'total' => $calcul->total,
            'total_lettres' => $calcul->total_en_lettres_ar,
            'bareme' => $calcul->details_calcul['bareme_applique'] ?? 'N/A',
            'formule' => $calcul->details_calcul['formule'] ?? 'N/A',
        ];
    }
}