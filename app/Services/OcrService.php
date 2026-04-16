<?php

namespace App\Services;

use RuntimeException;
use Smalot\PdfParser\Parser;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Http;

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
        return $this->runTesseract($absolutePath);
    }

    if ($ext !== 'pdf') {
        throw new RuntimeException('Format non supporté');
    }

    // PDF texte
    $text = $this->tryPdfParserText($absolutePath);

    if (mb_strlen(trim($text)) > 100) {
        return trim($text);
    }

    // PDF scanné
    $gs = $this->findGhostscript();

    if (!$gs) {
        throw new RuntimeException('Ghostscript manquant');
    }

    return $this->pdfViaGhostscript($absolutePath, $gs);
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

        $tmpImg = $tmpDir . DIRECTORY_SEPARATOR . uniqid('gs_', true) . '.png';

        $cmd = sprintf(
            '"%s" -dNOPAUSE -dBATCH -sDEVICE=png16m -r200 -dFirstPage=1 -dLastPage=10 -sOutputFile="%s" "%s" 2>&1',
            $gs,
            $tmpImg,
            $pdfPath
        );

        exec($cmd, $output, $code);

        if ($code !== 0 || ! is_file($tmpImg)) {
            throw new RuntimeException('Ghostscript n\'a pas pu convertir le PDF : ' . implode(' ', $output));
        }

        try {
            return $this->runTesseract($tmpImg);
        } finally {
            if (is_file($tmpImg)) {
                @unlink($tmpImg);
            }
        }
    }

    protected function tryPdfParserText(string $pdfPath): string
    {
        try {
            $parser = new Parser;
            $pdf    = $parser->parseFile($pdfPath);
            return trim((string) $pdf->getText());
        } catch (\Throwable $e) {
            return '';
        }
    }

    protected function runTesseract(string $imagePath): string
    {
        $ocr = new TesseractOCR($imagePath);
        $ocr->lang('ara+fra')
            ->psm(4)
            ->oem(1)
            ->dpi(300)
            ->config('preserve_interword_spaces', 1);

        $binary = config('assurances.tesseract_binary');
        if (is_string($binary) && $binary !== '') {
            $ocr->executable($binary);
        }

        return trim($ocr->run());
    }
}