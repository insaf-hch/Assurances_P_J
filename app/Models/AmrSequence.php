<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AmrSequence extends Model
{
    protected $fillable = [
        'year',
        'last_number',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'last_number' => 'integer',
        ];
    }

    /**
     * Prochain numéro d'أمر تنفيذي pour l'année civile (ex. 227 pour 227/26).
     */
    public static function nextForYear(int $year): int
    {
        return (int) DB::transaction(function () use ($year) {
            $row = static::query()->where('year', $year)->lockForUpdate()->first();
            if (! $row) {
                $row = new static(['year' => $year, 'last_number' => 0]);
            }
            $row->last_number = (int) $row->last_number + 1;
            $row->year = $year;
            $row->save();

            return $row->last_number;
        });
    }
}
