<?php

use App\Http\Controllers\DossierController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DossierController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DossierController::class, 'index']); // ← ajoute cette ligne
Route::post('/upload', [DossierController::class, 'upload'])->name('upload');
Route::post('/calculate/{dossier}', [DossierController::class, 'calculate'])->name('calculate');
Route::get('/generate-word/{id}', [DossierController::class, 'generateWord'])->name('generate-word');
Route::get('/phpinfo', function () { phpinfo(); });

Route::get('/test-upload', function () {
    echo 'upload_max: ' . ini_get('upload_max_filesize') . '<br>';
    echo 'post_max: ' . ini_get('post_max_size') . '<br>';
    echo 'max_time: ' . ini_get('max_execution_time') . '<br>';
});

Route::post('/test-direct', function (\Illuminate\Http\Request $request) {
    $file = $request->file('document');
    if (!$file) {
        return 'Aucun fichier reçu ! POST reçu : ' . json_encode($request->all());
    }
    return 'Fichier reçu : ' . $file->getClientOriginalName() . ' — taille : ' . $file->getSize();
});


use App\Http\Controllers\ManualController;

Route::post('/manual/store', [ManualController::class, 'store'])->name('manual.store');