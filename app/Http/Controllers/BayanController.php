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
        $bayan->group_index = $bayan->group_index + 15;
        return view('bayans.show', compact('bayan'));
    }
}
