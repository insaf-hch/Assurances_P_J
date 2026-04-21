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

            // Infos extraites du PDF
            $table->string('numero_dossier')->nullable();        // رقم الملف
            $table->string('numero_jugement')->nullable();       // رقم الحكم
            $table->date('date_jugement')->nullable();           // تاريخ القرار
            $table->date('date_accident')->nullable();           // تاريخ الحادثة
            $table->string('nom_victime')->nullable();           // اسم المصاب
            $table->string('nom_assurance')->nullable();         // شركة التأمين
            $table->string('adresse_assurance')->nullable();     // عنوان الشركة
            $table->string('nom_employeur')->nullable();         // المشغل

            // Type de cas (rempli lors du calcul; null après upload seul)
            $table->string('type_cas', 64)->nullable();

            // Nom assurance normalisé (ex: الوفاء) après comparaison avec le dossier local
            $table->string('nom_assurance_normalise')->nullable();

$table->decimal('montant_initial', 10, 2)->nullable();
$table->decimal('montant_rasemal_ijmali', 10, 2)->nullable();
$table->decimal('montant_taawidat_youmiya', 10, 2)->nullable();

            // Montants extraits du PDF
           $table->decimal('expertise', 10, 2)->nullable();        // الخبرة / تسبيقات الخزينة
            // Fichier PDF uploadé
            $table->string('fichier_pdf')->nullable();

            // Texte OCR extrait
            $table->longText('texte_ocr')->nullable();

            $table->timestamps();
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};