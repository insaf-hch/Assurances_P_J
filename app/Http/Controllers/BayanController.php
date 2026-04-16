<?php

namespace App\Http\Controllers;

use App\Models\Bayan;
use Illuminate\View\View;

class BayanController extends Controller
{
    public function show(Bayan $bayan): View
    {
        $bayan->load(['dossiers' => function ($q) {
            $q->with('calcul')->orderBy('id');
        }]);

        return view('bayans.show', compact('bayan'));
    }
}
