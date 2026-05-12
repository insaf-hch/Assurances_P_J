<?php

namespace App\Http\Controllers;

use App\Models\Dossier;
use App\Services\CalculService;
use App\Services\InsuranceDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManualController extends Controller
{
    public function __construct(
        protected InsuranceDetectionService $insuranceDetectionService,
        protected CalculService $calculService,
    ) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_dossier'           => 'nullable|string|max:255',
            'numero_jugement'          => 'nullable|string|max:255',
            'date_jugement'            => 'nullable|date',
            'nom_victime'              => 'nullable|string|max:255',
            'nom_assurance'            => 'nullable|string|max:255',
            'adresse_assurance'        => 'nullable|string|max:500',
            'montant_initial'          => 'nullable|numeric|min:0',
            'expertise'                => 'nullable|numeric|min:0',
            'type_cas'                 => 'nullable|in:irad_omri,irad_omri_ras_mal,masdar_total_taawidat,gharama_ijbariya,wafaya_irad_omri,wafaya_ras_mal,nizaat_shughl',
            'montant_rasemal_ijmali'   => 'nullable|numeric|min:0',
            'montant_taawidat_youmiya' => 'nullable|numeric|min:0',
            'montant_taawidat'         => 'nullable|numeric|min:0',
            'montant_masarif_tibiya'   => 'nullable|numeric|min:0',
            'masarif_janaza'           => 'nullable|numeric|min:0',
            'type_malaf'               => 'nullable|string|max:255',
            'beneficiaires'            => 'nullable|array',
            'beneficiaires.*.montant'  => 'nullable|numeric|min:0',
            'nizaat_darar'             => 'nullable|numeric|min:0',
            'nizaat_ikhtar'            => 'nullable|numeric|min:0',
            'nizaat_otla'              => 'nullable|numeric|min:0',
            'nizaat_aqdamiya'          => 'nullable|numeric|min:0',
        ]);

        $beneficiaires = [];
        foreach ($request->input('beneficiaires', []) as $row) {
            if (is_array($row)) {
                $beneficiaires[] = ['montant' => (float) ($row['montant'] ?? 0)];
            }
        }

        $nomNorm = $this->insuranceDetectionService->detectAndNormalize($validated['nom_assurance'] ?? null);
        $calculService = $this->calculService;

        DB::transaction(function () use ($validated, $beneficiaires, $nomNorm, $calculService) {
            $dossier = Dossier::create([
                'numero_dossier'           => $validated['numero_dossier'] ?? null,
                'numero_jugement'          => $validated['numero_jugement'] ?? null,
                'date_jugement'            => $validated['date_jugement'] ?? null,
                'nom_victime'              => $validated['nom_victime'] ?? null,
                'nom_assurance'            => $validated['nom_assurance'] ?? null,
                'nom_assurance_normalise'  => $nomNorm,
                'adresse_assurance'        => $validated['adresse_assurance'] ?? null,
                'montant_initial'          => (float) ($validated['montant_initial'] ?? 0),
                'montant_rasemal_ijmali'   => (float) ($validated['montant_rasemal_ijmali'] ?? 0),
                'montant_taawidat_youmiya' => (float) ($validated['montant_taawidat_youmiya'] ?? 0),
                'montant_taawidat'         => (float) ($validated['montant_taawidat'] ?? 0),
                'montant_masarif_tibiya'   => (float) ($validated['montant_masarif_tibiya'] ?? 0),
                'masarif_janaza'           => (float) ($validated['masarif_janaza'] ?? 0),
                'type_cas'                 => $validated['type_cas'] ?? null,
                'type_malaf'               => $validated['type_malaf'] ?? null,
                'expertise'                => (float) ($validated['expertise'] ?? 0),
                'beneficiaires_json'       => $beneficiaires !== [] ? $beneficiaires : null,
                'nizaat_darar'             => (float) ($validated['nizaat_darar'] ?? 0),
                'nizaat_ikhtar'            => (float) ($validated['nizaat_ikhtar'] ?? 0),
                'nizaat_otla'              => (float) ($validated['nizaat_otla'] ?? 0),
                'nizaat_aqdamiya'          => (float) ($validated['nizaat_aqdamiya'] ?? 0),
                'saved'                    => false,
            ]);

            if ($dossier->type_cas) {
                $calculService->createOrUpdateCalcul($dossier->fresh());
            }
        });

        return redirect()->route('dashboard')->with('success', 'تم تسجيل الملف يدوياً.');
    }
}