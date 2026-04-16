<?php

namespace App\Http\Controllers;

use App\Models\Bayan;
use App\Models\Dossier;
use App\Services\AiExtractionService;
use App\Services\CalculService;
use App\Services\DocumentGenerationService;
use App\Services\InsuranceDetectionService;
use App\Services\OcrService;
use App\Services\ProducedDocumentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DossierController extends Controller
{
    public function __construct(
        protected OcrService $ocrService,
        protected AiExtractionService $aiExtractionService,
        protected InsuranceDetectionService $insuranceDetectionService,
        protected CalculService $calculService,
        protected DocumentGenerationService $documentGenerationService,
        protected ProducedDocumentService $producedDocumentService,
    ) {}

    public function index(): View
    {
        $dossiers = Dossier::with(['calcul', 'bayan'])->latest()->paginate(15);
        $bayans = Bayan::query()
            ->where('year', (int) now()->year)
            ->withCount('dossiers')
            ->orderBy('group_index')
            ->get();

        return view('dashboard', compact('dossiers', 'bayans'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:81920',
        ]);
        $file = $request->file('document');
        if (! $file || ! $file->isValid()) {
            return back()->withErrors(['document' => 'ملف غير صالح.']);
        }

        $filename = uniqid('doc_', true).'.'.$file->getClientOriginalExtension();
        $uploadDir = storage_path('app/public/uploads');
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $absolute = $uploadDir.DIRECTORY_SEPARATOR.$filename;
        $file->move($uploadDir, $filename);
        

        try {
            $texteOcr = $this->ocrService->extractText($absolute);
            info('OCR FULL:', ['ocr' => $texteOcr]);
            $structured = $this->aiExtractionService->extractStructured($texteOcr);
            info('OCR extrait:', ['structured' => $structured, 'ocr' => substr($texteOcr, 0, 500)]);
            $nomNormalise = $this->insuranceDetectionService->detectAndNormalize($structured['nom_assurance'] ?? null);
            $montant = (float) ($structured['montant_initial'] ?? 0);

            $allowedTypes = [
                'irad_omri', 'irad_omri_ras_mal', 'masdar_total_taawidat', 'gharama_ijbariya',
                'wafaya_irad_omri', 'wafaya_ras_mal', 'autre',
            ];
            $typeCas = $structured['type_cas'] ?? null;
            if (! is_string($typeCas) || ! in_array($typeCas, $allowedTypes, true)) {
                $typeCas = null;
            }

            $dateJugement = null;
            if (! empty($structured['date_jugement'])) {
                try {
                    $dateJugement = Carbon::parse($structured['date_jugement'])->toDateString();
                } catch (\Throwable) {
                    $dateJugement = null;
                }
            }

            Dossier::create([
                'numero_dossier' => $structured['numero_dossier'] ?: null,
                'numero_jugement' => $structured['numero_jugement'] ?: null,
                'date_jugement' => $dateJugement,
                'nom_victime' => $structured['nom_victime'] ?: null,
                'nom_assurance' => $structured['nom_assurance'] ?: null,
                'nom_assurance_normalise' => $nomNormalise,
                'adresse_assurance' => $structured['adresse_assurance'] ?: null,
                'montant_initial' => $montant,
                'type_cas' => $typeCas,
                'expertise' => 0,
                'fichier_pdf' => 'uploads/'.$filename,
                'texte_ocr' => $texteOcr,
                'saved' => false,
            ]);
        } catch (\Throwable $e) {
            if (is_file($absolute)) {
                @unlink($absolute);
            }

            return back()->withErrors(['document' => $e->getMessage()]);
        }

        return redirect()->route('dashboard')->with('success', 'تم تحليل المستند بنجاح.');
    }

    public function calculate(Request $request, Dossier $dossier)
    {
        $validated = $request->validate([
            'type_cas' => 'required|in:irad_omri,irad_omri_ras_mal,masdar_total_taawidat,gharama_ijbariya,wafaya_irad_omri,wafaya_ras_mal,autre',
            'expertise' => 'nullable|numeric|min:0',
            'montant_initial' => 'nullable|numeric|min:0',
            'montant_rasemal_ijmali' => 'nullable|numeric|min:0',
            'montant_taawidat_youmiya' => 'nullable|numeric|min:0',
            'masarif_janaza' => 'nullable|numeric|min:0',
            'type_malaf' => 'nullable|string|max:255',
            'beneficiaires' => 'nullable|array',
            'beneficiaires.*.montant' => 'nullable|numeric|min:0',
            'beneficiaires_json' => 'nullable|string',
        ]);

        $beneficiaires = [];
        if ($request->has('beneficiaires_json')) {
            $decoded = json_decode($request->input('beneficiaires_json') ?: '[]', true);
            if (is_array($decoded)) {
                foreach ($decoded as $row) {
                    if (is_array($row)) {
                        $beneficiaires[] = ['montant' => (float) ($row['montant'] ?? 0)];
                    }
                }
            }
            $beneficiairesJson = $beneficiaires;
        } elseif ($request->has('beneficiaires') && is_array($request->input('beneficiaires'))) {
            foreach ($request->input('beneficiaires', []) as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $beneficiaires[] = ['montant' => (float) ($row['montant'] ?? 0)];
            }
            $beneficiairesJson = $beneficiaires;
        } else {
            $beneficiairesJson = $dossier->beneficiaires_json;
        }

        DB::transaction(function () use ($dossier, $validated, $beneficiairesJson) {
            $dossier->update([
                'type_cas' => $validated['type_cas'],
                'expertise' => (float) ($validated['expertise'] ?? 0),
                'montant_initial' => (float) ($validated['montant_initial'] ?? $dossier->montant_initial),
                'montant_rasemal_ijmali' => (float) ($validated['montant_rasemal_ijmali'] ?? $dossier->montant_rasemal_ijmali),
                'montant_taawidat_youmiya' => (float) ($validated['montant_taawidat_youmiya'] ?? $dossier->montant_taawidat_youmiya),
                'masarif_janaza' => (float) ($validated['masarif_janaza'] ?? $dossier->masarif_janaza),
                'type_malaf' => $validated['type_malaf'] ?? $dossier->type_malaf,
                'beneficiaires_json' => $beneficiairesJson,
            ]);

            $this->calculService->createOrUpdateCalcul($dossier->fresh());
        });

        return redirect()->route('dashboard')->with('success', 'تم حفظ الحساب.');
    }

    public function update(Request $request, Dossier $dossier)
    {
        $validated = $request->validate([
            'numero_dossier' => 'nullable|string|max:255',
            'numero_jugement' => 'nullable|string|max:255',
            'date_jugement' => 'nullable|date',
            'nom_victime' => 'nullable|string|max:255',
            'nom_assurance' => 'nullable|string|max:255',
            'adresse_assurance' => 'nullable|string|max:500',
            'montant_initial' => 'nullable|numeric|min:0',
            'expertise' => 'nullable|numeric|min:0',
            'type_cas' => 'nullable|in:irad_omri,irad_omri_ras_mal,masdar_total_taawidat,gharama_ijbariya,wafaya_irad_omri,wafaya_ras_mal,autre',
            'montant_rasemal_ijmali' => 'nullable|numeric|min:0',
            'montant_taawidat_youmiya' => 'nullable|numeric|min:0',
            'masarif_janaza' => 'nullable|numeric|min:0',
            'type_malaf' => 'nullable|string|max:255',
            'beneficiaires' => 'nullable|array',
            'beneficiaires.*.montant' => 'nullable|numeric|min:0',
            'beneficiaires_json' => 'nullable|string',
        ]);

        $beneficiaires = $dossier->beneficiaires_json ?? [];
        if ($request->has('beneficiaires_json')) {
            $decoded = json_decode($request->input('beneficiaires_json') ?: '[]', true);
            $beneficiaires = [];
            if (is_array($decoded)) {
                foreach ($decoded as $row) {
                    if (is_array($row)) {
                        $beneficiaires[] = ['montant' => (float) ($row['montant'] ?? 0)];
                    }
                }
            }
        } elseif ($request->has('beneficiaires')) {
            $beneficiaires = [];
            foreach ($request->input('beneficiaires', []) as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $beneficiaires[] = ['montant' => (float) ($row['montant'] ?? 0)];
            }
        }

        $dossier->update(array_merge(
            collect($validated)->except(['beneficiaires', 'beneficiaires_json'])->toArray(),
            ['beneficiaires_json' => $beneficiaires]
        ));

        if ($dossier->type_cas) {
            $this->calculService->createOrUpdateCalcul($dossier->fresh());
        }

        return redirect()->route('dashboard')->with('success', 'تم تعديل الملف.');
    }

    public function destroy(Dossier $dossier)
    {
        $dossier->delete();

        return redirect()->route('dashboard')->with('success', 'تم حذف الملف.');
    }

    public function breakdown(Dossier $dossier): JsonResponse
    {
        return response()->json($this->calculService->buildBreakdown($dossier));
    }

    public function saveState(Request $request, Dossier $dossier): JsonResponse
    {
        $data = $request->validate([
            'saved' => 'required|boolean',
        ]);

        try {
            $this->producedDocumentService->setSaved($dossier->fresh(), (bool) $data['saved']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true]);
    }

    public function printIstidaa(Dossier $dossier): View
    {
        $dossier->load('calcul');
        abort_if(! $dossier->calcul, 404);

        return view('prints.istidaa', ['dossier' => $dossier, 'calcul' => $dossier->calcul]);
    }

    public function printAmr(Dossier $dossier): View
    {
        $dossier->load('calcul');
        abort_if(! $dossier->calcul, 404);

        return view('prints.amr', ['dossier' => $dossier, 'calcul' => $dossier->calcul]);
    }

    public function generateWord(int $id): BinaryFileResponse
    {
        $dossier = Dossier::with('calcul')->findOrFail($id);
        if (! $dossier->calcul) {
            abort(404, 'Aucun calcul pour ce dossier.');
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
