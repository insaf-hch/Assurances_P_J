<?php
use App\Http\Controllers\BayanController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\WathaiqController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ✅ Page d'accueil — PREMIÈRE et UNIQUE route pour "/"
Route::get('/accueil', function () {
    return view('accueil');
})->name('accueil');

// ✅ Dashboard — après connexion
Route::get('/dashboard',
[DossierController::class, 'index'])
->middleware('auth')
->name('dashboard');

Route::post('/logout',
[AuthController::class, 'logout'])
->name('logout');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/upload', [DossierController::class, 'upload'])->name('upload');
Route::post('/manual/store', [ManualController::class, 'store'])->name('manual.store');
Route::post('/calculate/{dossier}', [DossierController::class, 'calculate'])->name('calculate');

Route::put('/dossiers/{dossier}', [DossierController::class, 'update'])->name('dossiers.update');
Route::delete('/dossiers/{dossier}', [DossierController::class, 'destroy'])->name('dossiers.destroy');

Route::get('/dossiers/{dossier}/breakdown', [DossierController::class, 'breakdown'])->name('dossiers.breakdown');
Route::post('/dossiers/{dossier}/save', [DossierController::class, 'saveState'])->name('dossiers.save');

Route::get('/dossiers/{dossier}/print/istidaa', [DossierController::class, 'printIstidaa'])->name('dossiers.print.istidaa');
Route::get('/dossiers/{dossier}/print/amr', [DossierController::class, 'printAmr'])->name('dossiers.print.amr');

Route::get('/generate-word/{id}', [DossierController::class, 'generateWord'])->name('generate-word');

Route::get('/bayans/{bayan}', [BayanController::class, 'show'])->name('bayans.show');
Route::get('/bayans/{bayan}/donn', function(\App\Models\Bayan $bayan) {
    return view('bayans.DonnBaayan', compact('bayan'));
})->name('bayans.donn');

Route::get('/wathaiq', [WathaiqController::class, 'index'])->name('wathaiq.index');