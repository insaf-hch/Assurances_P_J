<?php

namespace App\Services;

use App\Models\AmrSequence;
use App\Models\Bayan;
use App\Models\Dossier;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Enregistrement dans « الوثائق المُنتجة » : بيان (30 dossiers max) + numéro أمر تنفيذي.
 */
class ProducedDocumentService
{
    public function setSaved(Dossier $dossier, bool $saved): void
    {
        if (! $saved) {
            $dossier->update([
                'saved' => false,
                'bayan_id' => null,
            ]);
            if ($dossier->calcul) {
                $dossier->calcul->update(['numero_amr_tanfidhi' => null]);
            }

            return;
        }

        if (! $dossier->calcul) {
            throw new RuntimeException('يجب إجراء الحساب قبل التسجيل في الوثائق المُنتجة.');
        }

        if ($dossier->bayan_id && $dossier->saved) {
            return;
        }

        DB::transaction(function () use ($dossier) {
            $year = (int) now()->year;
            $bayan = $this->findBayanWithFreeSlot($year);
            if (! $bayan) {
                $max = (int) Bayan::where('year', $year)->max('group_index');
                $bayan = Bayan::create([
                    'year' => $year,
                    'group_index' => $max + 1,
                ]);
            }

            $dossier->update([
                'bayan_id' => $bayan->id,
                'saved' => true,
            ]);

            $n = AmrSequence::nextForYear($year);
            $yy = substr((string) $year, -2);
            $dossier->calcul->update([
                'numero_amr_tanfidhi' => $n.'/'.$yy,
            ]);
        });
    }

    protected function findBayanWithFreeSlot(int $year): ?Bayan
    {
        $candidates = Bayan::query()
            ->where('year', $year)
            ->withCount('dossiers')
            ->orderBy('id')
            ->get();

        foreach ($candidates as $b) {
            if ($b->dossiers_count < 30) {
                return $b;
            }
        }

        return null;
    }
}
