<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');

            // Étape de calcul selon le cas
            $table->decimal('montant_apres_cas', 10, 2)->default(0);
            // Cas 1 : montant × 10
            // Cas 2 & 3 : montant direct

            // الرسم القضائي
            $table->decimal('rasm_qadai', 10, 2)->default(0);

            // Frais fixes
            $table->decimal('rusum_murafaa', 10, 2)->default(10.00);  // حقوق المرافعة
            $table->decimal('rasm_bahth', 10, 2)->default(20.00);     // رسم البحث
            $table->decimal('expertise', 10, 2)->default(0);          // الخبرة

            // Total final
            $table->decimal('total', 10, 2)->default(0);              // المجموع
            $table->string('total_en_lettres_ar')->nullable();        // المجموع بالحروف

            // Numéro أمر تنفيذي
            $table->string('numero_amr_tanfidhi')->nullable();

            // Date génération document
            $table->date('date_generation')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculs');
    }
};