<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      Schema::create('dossiers', function (Blueprint $table) {
    $table->id();
    $table->string('numero_dossier')->nullable();
    $table->string('numero_jugement')->nullable();
    $table->date('date_jugement')->nullable();
    $table->date('date_accident')->nullable();
    $table->string('nom_victime')->nullable();
    $table->string('Adres_victime')->nullable();
    $table->string('Avocat')->nullable();
    $table->string('nom_assurance')->nullable();
    $table->string('adresse_assurance')->nullable();
    $table->string('nom_employeur')->nullable();
    $table->string('type_cas', 64)->nullable();
    $table->string('type_malaf')->nullable();
    $table->string('nom_assurance_normalise')->nullable();
    $table->decimal('montant_initial', 10, 2)->nullable();
    $table->decimal('montant_rasemal_ijmali', 10, 2)->nullable();
    $table->decimal('montant_taawidat_youmiya', 10, 2)->nullable();
    $table->decimal('montant_taawidat', 10, 2)->nullable();
    $table->decimal('montant_masarif_tibiya', 10, 2)->nullable();
    $table->decimal('masarif_janaza', 10, 2)->nullable();
    $table->decimal('expertise', 10, 2)->nullable();
    $table->json('beneficiaires_json')->nullable();
    $table->boolean('saved')->default(false);
    // نزاعات الشغل
    $table->decimal('nizaat_darar', 10, 2)->nullable();
    $table->decimal('nizaat_ikhtar', 10, 2)->nullable();
    $table->decimal('nizaat_otla', 10, 2)->nullable();
    $table->decimal('nizaat_aqdamiya', 10, 2)->nullable();
    $table->unsignedBigInteger('bayan_id')->nullable(); // ← sans constrained()
    $table->string('fichier_pdf')->nullable();
    $table->longText('texte_ocr')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};