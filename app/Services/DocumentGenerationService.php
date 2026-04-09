<?php

namespace App\Services;

use App\Models\Calcul;
use App\Models\Dossier;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;

/**
 * Génération à partir de modèles Word : أمر تنفيذي et استدعـــــاء.
 */
class DocumentGenerationService
{
    private const TEMPLATES = [
        'amr_tanfidhi.docx' => 'amr_tanfidhi',
        'istidaa.docx' => 'istidaa',
    ];

    /**
     * Crée les .docx modèles avec placeholders ${...} s'ils n'existent pas encore.
     */
    public function ensureTemplatesExist(): void
    {
        $dir = storage_path('app/templates');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $definitions = [
            'amr_tanfidhi.docx' => 'أمر تنفيذي',
            'istidaa.docx' => 'استدعـــــاء',
        ];

        foreach ($definitions as $file => $title) {
            $path = $dir.DIRECTORY_SEPARATOR.$file;
            if (is_file($path)) {
                continue;
            }

            $phpWord = new PhpWord;
            $section = $phpWord->addSection();
            $section->addText($title, ['bold' => true, 'size' => 16]);
            $section->addTextBreak(2);
            $section->addText('رقم الملف: ${numero_dossier}');
            $section->addTextBreak(1);
            $section->addText('شركة التأمين: ${nom_assurance}');
            $section->addTextBreak(1);
            $section->addText('المبلغ: ${montant}');
            $section->addTextBreak(1);
            $section->addText('المجموع: ${total}');
            $section->addTextBreak(1);
            $section->addText('التاريخ: ${date}');

            IOFactory::createWriter($phpWord, 'Word2007')->save($path);
        }
    }

    /**
     * @return array{0: string, 1: string} chemins absolus des deux documents générés
     */
    public function generateFilledDocuments(Dossier $dossier, Calcul $calcul): array
    {
        $this->ensureTemplatesExist();

        $dateStr = $dossier->date_jugement
            ? $dossier->date_jugement->format('Y-m-d')
            : now()->format('Y-m-d');

        $montantStr = number_format((float) $calcul->montant_apres_cas, 2, '.', '');
        $totalStr = number_format((float) $calcul->total, 2, '.', '');
        $nomAssurance = $dossier->nom_assurance_normalise
            ?? $dossier->nom_assurance
            ?? '';

        $out = [];
        $tempDir = storage_path('app/temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        foreach (self::TEMPLATES as $tplFile => $prefix) {
            $tplPath = storage_path('app/templates/'.$tplFile);
            $processor = new TemplateProcessor($tplPath);
            $processor->setValue('numero_dossier', (string) ($dossier->numero_dossier ?? ''));
            $processor->setValue('nom_assurance', $nomAssurance);
            $processor->setValue('montant', $montantStr);
            $processor->setValue('total', $totalStr);
            $processor->setValue('date', $dateStr);

            $target = $tempDir.DIRECTORY_SEPARATOR.$dossier->id.'_'.$prefix.'_'.uniqid('', true).'.docx';
            $processor->saveAs($target);
            $out[] = $target;
        }

        return $out;
    }

    /**
     * Archive ZIP contenant les deux documents (téléchargement unique).
     */
    public function makeZipArchive(array $absoluteDocxPaths, string $zipBasename): string
    {
        $tempDir = storage_path('app/temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipPath = $tempDir.DIRECTORY_SEPARATOR.$zipBasename;
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Impossible de créer l\'archive ZIP.');
        }

        $names = ['amr_tanfidhi.docx', 'istidaa.docx'];
        foreach ($absoluteDocxPaths as $i => $path) {
            $internal = $names[$i] ?? ('document_'.($i + 1).'.docx');
            $zip->addFile($path, $internal);
        }
        $zip->close();

        return $zipPath;
    }
}
