<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bayan extends Model
{
    protected $fillable = [
        'year',
        'group_index',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'group_index' => 'integer',
        ];
    }

    public function dossiers(): HasMany
    {
        return $this->hasMany(Dossier::class);
    }

    /**
     * Libellé d'affichage : groupe de 30 dossiers pour l'année.
     */
    public function getRangeLabelAttribute(): string
    {
        $yy = substr((string) $this->year, -2);
        $start = (($this->group_index - 1) * 30) + 1;
        $end = $this->group_index * 30;

        return "من {$start} إلى {$end}/{$yy}";
    }
}
