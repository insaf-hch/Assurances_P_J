<?php
use App\Http\Controllers\BayanController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\WathaiqController; // ✅ AJOUT
use Illuminate\Support\Facades\Route;

Route::get('/', [DossierController::class, 'index'])->name('home');
Route::get('/dashboard', [DossierController::class, 'index'])->name('dashboard');

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

Route::get('/wathaiq', [WathaiqController::class, 'index'])->name('wathaiq.index');