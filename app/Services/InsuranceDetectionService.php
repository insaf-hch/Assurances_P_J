<?php

namespace App\Services;

/**
 * Compare le nom d'assurance extrait avec les noms sous INSURANCE_SCAN_PATH
 * et détecte notamment شركة الوفاء.
 */
class InsuranceDetectionService
{
    public function detectAndNormalize(?string $nomAssuranceExtracted): string
    {
        $alWafaa = (string) config('assurances.al_wafaa_label', 'الوفاء');
        $path = (string) config('assurances.insurance_scan_path');

        if ($nomAssuranceExtracted === null || trim($nomAssuranceExtracted) === '') {
            return 'غير محدد';
        }

        $extractedNorm = $this->normalizeForCompare($nomAssuranceExtracted);

        if (str_contains($extractedNorm, $this->normalizeForCompare($alWafaa))) {
            return $alWafaa;
        }

        if (! is_dir($path) || ! is_readable($path)) {
            return 'أخرى';
        }

        $entries = @scandir($path) ?: [];
        $bestLabel = null;
        $bestScore = 0.0;

        foreach ($entries as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $label = pathinfo($entry, PATHINFO_FILENAME);
            if ($label === '' || $label === $entry) {
                $label = $entry;
            }

            $entryNorm = $this->normalizeForCompare($label);
            if ($entryNorm === '') {
                continue;
            }

            similar_text($extractedNorm, $entryNorm, $pct);
            $contains = str_contains($extractedNorm, $entryNorm) || str_contains($entryNorm, $extractedNorm);
            $score = $contains ? max($pct, 55.0) : $pct;

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestLabel = $label;
            }

            if (str_contains($entryNorm, $this->normalizeForCompare($alWafaa))) {
                return $alWafaa;
            }
        }

        if ($bestLabel !== null && $bestScore >= 20.0) {
            if (str_contains($this->normalizeForCompare($bestLabel), $this->normalizeForCompare($alWafaa))) {
                return $alWafaa;
            }

            return trim($bestLabel);
        }

        return 'أخرى';
    }

    protected function normalizeForCompare(string $text): string
    {
        $text = preg_replace('/\s+/u', ' ', $text);

        return mb_strtolower(trim($text), 'UTF-8');
    }
}
