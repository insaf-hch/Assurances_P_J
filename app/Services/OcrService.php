<?php

namespace App\Services;

use RuntimeException;
use Smalot\PdfParser\Parser;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    private const MIN_PDF_TEXT_CHARS = 40;

    public function extractText(string $absolutePath): string
    {
        if (! is_readable($absolutePath)) {
    throw new RuntimeException('Fichier illisible. Chemin : ' . $absolutePath . ' — existe : ' . (file_exists($absolutePath) ? 'oui' : 'non'));
}

        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'tif', 'tiff'], true)) {
            return $this->runTesseract($absolutePath);
        }

        if ($ext === 'pdf') {
            return $this->extractFromPdf($absolutePath);
        }

        throw new RuntimeException('Extension non prise en charge. Utilisez PDF, JPG ou PNG.');
    }

    protected function extractFromPdf(string $pdfPath): string
    {
        // Essai 1 : texte intégré
        $embedded = $this->tryPdfParserText($pdfPath);
        if (mb_strlen(trim($embedded)) >= self::MIN_PDF_TEXT_CHARS) {
            return trim($embedded);
        }

        // Essai 2 : Ghostscript → image → Tesseract
        $ghostscript = $this->findGhostscript();
        if ($ghostscript) {
            return $this->pdfViaGhostscript($pdfPath, $ghostscript);
        }

        throw new RuntimeException(
            'PDF scanné sans texte intégré. Installez Ghostscript ou téléversez une image PNG/JPG.'
        );
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
    '"%s" -dNOPAUSE -dBATCH -sDEVICE=png16m -r200 -dFirstPage=1 -dLastPage=1 -sOutputFile="%s" "%s" 2>&1',
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
            $pdf = $parser->parseFile($pdfPath);
            return trim((string) $pdf->getText());
        } catch (\Throwable) {
            return '';
        }
    }

    protected function runTesseract(string $imagePath): string
    {
        $ocr = new TesseractOCR($imagePath);
        $ocr->lang('ara', 'fra');

        $binary = config('assurances.tesseract_binary');
        if (is_string($binary) && $binary !== '') {
            $ocr->executable($binary);
        }

        return trim($ocr->run());
    }
}