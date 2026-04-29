<?php

namespace App\Services;

use RuntimeException;
use Smalot\PdfParser\Parser;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;  // ← AJOUTEZ CETTE LIGNE

class OcrService
{
    public function extractText(string $absolutePath): string
    {
        if (!file_exists($absolutePath)) {
            throw new RuntimeException('Fichier introuvable');
        }

        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        // IMAGE → OCR
        if (in_array($ext, ['jpg','jpeg','png','gif','tif','tiff'], true)) {
            $text = $this->runTesseract($absolutePath);
            Log::info('Texte OCR brut (500 chars):', [substr($text, 0, 500)]);  // ← Log APRÈS avoir le texte
            return $text;
        }

        if ($ext !== 'pdf') {
            throw new RuntimeException('Format non supporté');
        }

        // PDF texte
        $text = $this->tryPdfParserText($absolutePath);

        if (mb_strlen(trim($text)) > 100) {
            Log::info('Texte PDF texte (500 chars):', [substr($text, 0, 500)]);
            return trim($text);
        }

        // PDF scanné
        $gs = $this->findGhostscript();

        if (!$gs) {
            throw new RuntimeException('Ghostscript manquant');
        }

        $text = $this->pdfViaGhostscript($absolutePath, $gs);
        Log::info('Texte PDF scanné (500 chars):', [substr($text, 0, 500)]);
        
        return trim($text);
    }

    protected function findGhostscript(): ?string
    {
        $candidates = [
            'C:\\Program Files\\gs\\gs10.07.0\\bin\\gswin64c.exe',
            config('assurances.ghostscript_binary'),
        ];

        foreach ($candidates as $gs) {
            if (is_string($gs) && $gs !== '' && file_exists($gs)) {
                return $gs;
            }
        }

        return null;
    }

    protected function pdfViaGhostscript(string $pdfPath, string $gs): string
    {
        $tmpDir = storage_path('app/temp');
        if (! is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $prefix = $tmpDir . DIRECTORY_SEPARATOR . uniqid('gs_', true);
        $pattern = $prefix . '_%03d.png';

        $cmd = sprintf(
            '"%s" -dNOPAUSE -dBATCH -sDEVICE=png16m -r300 -dFirstPage=1 -dLastPage=5 -sOutputFile="%s" "%s" 2>&1',
            $gs,
            $pattern,
            $pdfPath
        );

        exec($cmd, $output, $code);

        $pages = glob($prefix . '_*.png');

        if ($code !== 0 || empty($pages)) {
            throw new RuntimeException('Ghostscript n\'a pas pu convertir le PDF : ' . implode(' ', $output));
        }

        sort($pages);

        $fullText = '';
        foreach ($pages as $page) {
            try {
                $pageText = $this->runTesseract($page);
                $fullText .= "\n" . $pageText;
                Log::info('Page OCR:', ['page' => basename($page), 'text' => substr($pageText, 0, 200)]);
            } finally {
                if (is_file($page)) {
                    @unlink($page);
                }
            }
        }

        return trim($fullText);
    }

    protected function tryPdfParserText(string $pdfPath): string
    {
        try {
            $parser = new Parser;
            $pdf    = $parser->parseFile($pdfPath);
            return trim((string) $pdf->getText());
        } catch (\Throwable $e) {
            Log::warning('PdfParser a échoué:', ['error' => $e->getMessage()]);
            return '';
        }
    }

    protected function runTesseract(string $imagePath): string
    {
        $ocr = new TesseractOCR($imagePath);
        $ocr->lang('ara+fra')
            ->psm(6)
            ->oem(1)
            ->dpi(300)
            ->config('preserve_interword_spaces', '1');

        $binary = config('assurances.tesseract_binary');
        if (is_string($binary) && $binary !== '') {
            $ocr->executable($binary);
        }

        $result = trim($ocr->run());
        Log::info('Tesseract résultat:', ['length' => strlen($result), 'preview' => substr($result, 0, 200)]);
        
        return $result;
    }
}