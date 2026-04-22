<?php

namespace App\Services;

class InsuranceDetectionService
{
    // Mapping : mots-clés (normalisés) → nom exact du dossier
    private const KNOWN_COMPANIES = [
        'وفاء'             => 'شركة التأمين الوفاء',
        'اكسا'             => 'شركة التأمين أكسا',
        'اكسا للتامين'     => 'شركة التأمين أكسا',
        'ملكية'            => 'شركة التأمين الملكية',
        'زوريخ'            => 'شركة تأمين زوريخ',
        'سند'              => 'شركة التأمين سند',
        'سنلام'             => 'شركة تأمين سنلام',
        'اطلنطا'           => 'شركة التأمين اطلنطا',
        'ارباب النقل'      => 'شركة تأمين ارباب النقل',
        'تامين النقل'      => 'شركة التأمين النقل',
        'النقل'            => 'شركة التأمين النقل',
        'المكتب المركزي'   => 'المكتب المركزي',
        'مركزي'            => 'المكتب المركزي',
        'تعاضدية'          => 'التعاضدية الفلاحية أو المركزية',
        'فلاحية'           => 'التعاضدية الفلاحية أو المركزية',
        'اليانز'           => 'شركة تأمين اليانز المغرب',
    ];

    public function detectAndNormalize(?string $nomAssuranceExtracted): string
    {
        if ($nomAssuranceExtracted === null || trim($nomAssuranceExtracted) === '') {
            return 'غير محدد';
        }

        $extractedNorm = $this->normalizeForCompare($nomAssuranceExtracted);

        // 1. Correspondance directe par mots-clés
        foreach (self::KNOWN_COMPANIES as $keyword => $label) {
            if (str_contains($extractedNorm, $this->normalizeForCompare($keyword))) {
                return $label;
            }
        }

        // 2. Fallback : correspondance fuzzy via dossiers sur disque
        $path = (string) config('assurances.insurance_scan_path');
        if (! is_dir($path) || ! is_readable($path)) {
            return $nomAssuranceExtracted; // retourner tel quel si pas de dossiers
        }

        $entries = @scandir($path) ?: [];
        $bestLabel = null;
        $bestScore = 0.0;

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $label = pathinfo($entry, PATHINFO_FILENAME);
            $entryNorm = $this->normalizeForCompare($label);
            if ($entryNorm === '') continue;

            // Vérifier aussi via KNOWN_COMPANIES
            foreach (self::KNOWN_COMPANIES as $keyword => $knownLabel) {
                if (str_contains($entryNorm, $this->normalizeForCompare($keyword))) {
                    similar_text($extractedNorm, $entryNorm, $pct);
                    if ($pct > $bestScore) {
                        $bestScore = $pct;
                        $bestLabel = $knownLabel; // utiliser le nom normalisé
                    }
                }
            }

            similar_text($extractedNorm, $entryNorm, $pct);
            if ($pct > $bestScore) {
                $bestScore = $pct;
                $bestLabel = $label;
            }
        }

        if ($bestLabel !== null && $bestScore >= 40.0) {
            return trim($bestLabel);
        }

        return $nomAssuranceExtracted; // retourner le nom original si rien trouvé
    }

    protected function normalizeForCompare(string $text): string
    {
        // Supprimer tashkeel (diacritiques arabes)
        $text = preg_replace('/[\x{064B}-\x{065F}]/u', '', $text);
        // Normaliser les alef
        $text = preg_replace('/[أإآا]/u', 'ا', $text);
        // Supprimer "شركة" et "التأمين" pour comparer le nom réel
        $text = str_replace(['شركة', 'التأمين', 'تأمين', 'شرمة'], '', $text);
        $text = preg_replace('/\s+/u', ' ', $text);

        return mb_strtolower(trim($text), 'UTF-8');
    }
}