<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Services\AiExtractionService;
use App\Services\CalculService;
use App\Services\DocumentGenerationService;
use App\Services\InsuranceDetectionService;
use App\Services\OcrService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Log;

class DossierController extends Controller
{
    public function __construct(
        protected OcrService $ocrService,
        protected AiExtractionService $aiExtractionService,
        protected InsuranceDetectionService $insuranceDetectionService,
        protected CalculService $calculService,
        protected DocumentGenerationService $documentGenerationService,
    ) {}

    /**
     * Tableau de bord : liste des dossiers + formulaire d'envoi.
     */
   public function index()
{
    $dossiers = Dossier::with('calcul')->latest()->paginate(15);
    return view('dashboard', compact('dossiers'));
}


    /**
     * Upload → OCR (Tesseract ara) → IA → enregistrement dossier.
     */
   public function upload(Request $request)
{
    // Debug : voir tout ce qui arrive
    Log::info('Upload lancé');
    Log::info('Fichiers reçus : ' . json_encode($_FILES));
    
    $request->validate([
        'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:81920',
    ]);

    $file = $request->file('document');

    if (!$file || !$file->isValid()) {
        $error = $file ? $file->getErrorMessage() : 'aucun fichier reçu';
        Log::error('Fichier invalide : ' . $error);
        return back()->withErrors(['document' => 'Erreur : ' . $error]);
    }

    Log::info('Fichier valide : ' . $file->getClientOriginalName() . ' taille: ' . $file->getSize());

    $filename = uniqid('doc_', true) . '.' . $file->getClientOriginalExtension();
    $uploadDir = storage_path('app/uploads');
    $absolute = $uploadDir . DIRECTORY_SEPARATOR . $filename;

    $moved = $file->move($uploadDir, $filename);
    Log::info('Fichier déplacé : ' . ($moved ? 'oui' : 'non') . ' vers : ' . $absolute);

    if (!file_exists($absolute)) {
        Log::error('Fichier introuvable après move : ' . $absolute);
        return back()->withErrors(['document' => 'Fichier non sauvegardé !']);
    }

    Log::info('Fichier sauvegardé avec succès !');

    try {
        $texteOcr = $this->ocrService->extractText($absolute);
        Log::info('OCR OK : ' . substr($texteOcr, 0, 200));

        $structured = $this->aiExtractionService->extractStructured($texteOcr);
        Log::info('IA OK : ' . json_encode($structured));

        $nomNormalise = $this->insuranceDetectionService->detectAndNormalize($structured['nom_assurance'] ?? null);
        $montant = (float) ($structured['montant_initial'] ?? 0);

      Dossier::create([
    'numero_dossier'          => $structured['numero_dossier'] ?: null,
    'numero_jugement'         => $structured['numero_jugement'] ?: null,
    'date_jugement'           => null,
    'nom_victime'             => $structured['nom_victime'] ?: null,
    'nom_assurance'           => $structured['nom_assurance'] ?: null,
    'nom_assurance_normalise' => $nomNormalise,
    'adresse_assurance'       => $structured['adresse_assurance'] ?: null,
    'montant_initial'         => $montant,
    'type_cas'                => $structured['type_cas'] ?: null,
    'expertise'               => 0,
    'fichier_pdf'             => 'uploads/' . $filename,
    'texte_ocr'               => $texteOcr,
]);

        Log::info('Dossier sauvegardé en base !');

    } catch (\Throwable $e) {
        Log::error('Erreur : ' . $e->getMessage());
        return back()->withErrors(['document' => $e->getMessage()]);
    }

    return redirect('/')->with('success', 'Document traité avec succès !');
}

    /**
     * Application des 4 cas + الرسم القضائي + total.
     */
    public function calculate(Request $request, Dossier $dossier)
    {
        $validated = $request->validate([
            'type_cas' => 'required|in:irad_omri,irad_omri_ras_mal,gharama_ijbariya,autre',
            'expertise' => 'nullable|numeric|min:0',
        ]);

        $expertise = (float) ($validated['expertise'] ?? 0);

        DB::transaction(function () use ($dossier, $validated, $expertise) {
            $dossier->update([
                'type_cas' => $validated['type_cas'],
                'expertise' => $expertise,
            ]);
            $this->calculService->createOrUpdateCalcul($dossier->fresh(), $validated['type_cas'], $expertise);
        });

        return redirect('/dashboard')->with('success', 'Calcul enregistré');
    }

    /**
     * Génère les deux Word (أمر تنفيذي + استدعاء) dans une archive ZIP.
     */
    public function generateWord(int $id): BinaryFileResponse
    {
        $dossier = Dossier::with('calcul')->findOrFail($id);
        if (! $dossier->calcul) {
            abort(404, 'Aucun calcul pour ce dossier. Exécutez d\'abord le calcul.');
        }

        $paths = $this->documentGenerationService->generateFilledDocuments($dossier, $dossier->calcul);
        $zip = $this->documentGenerationService->makeZipArchive($paths, 'dossier_'.$dossier->id.'_documents.zip');

        foreach ($paths as $p) {
            if (is_file($p)) {
                @unlink($p);
            }
        }

        return response()->download($zip, 'dossier_'.$dossier->id.'_documents.zip')->deleteFileAfterSend(true);
    }
}

