<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (! Schema::hasColumn('dossiers', 'saved')) {
                $table->boolean('saved')->default(false)->after('texte_ocr');
            }
            if (! Schema::hasColumn('dossiers', 'bayan_id')) {
                $table->foreignId('bayan_id')->nullable()->after('saved')->constrained('bayans')->nullOnDelete();
            }
            if (! Schema::hasColumn('dossiers', 'type_malaf')) {
                $table->string('type_malaf')->nullable()->after('type_cas');
            }
            if (! Schema::hasColumn('dossiers', 'montant_rasemal_ijmali')) {
                $table->decimal('montant_rasemal_ijmali', 12, 2)->default(0)->after('montant_initial');
            }
            if (! Schema::hasColumn('dossiers', 'montant_taawidat_youmiya')) {
                $table->decimal('montant_taawidat_youmiya', 12, 2)->default(0)->after('montant_rasemal_ijmali');
            }
            if (! Schema::hasColumn('dossiers', 'beneficiaires_json')) {
                $table->json('beneficiaires_json')->nullable()->after('montant_taawidat_youmiya');
            }
            if (! Schema::hasColumn('dossiers', 'masarif_janaza')) {
                $table->decimal('masarif_janaza', 12, 2)->default(0)->after('beneficiaires_json');
            }
        });

        Schema::table('calculs', function (Blueprint $table) {
            if (! Schema::hasColumn('calculs', 'masarif_janaza')) {
                $table->decimal('masarif_janaza', 12, 2)->default(0)->after('expertise');
            }
        });
    }

    public function down(): void
    {
        Schema::table('calculs', function (Blueprint $table) {
            if (Schema::hasColumn('calculs', 'masarif_janaza')) {
                $table->dropColumn('masarif_janaza');
            }
        });

        Schema::table('dossiers', function (Blueprint $table) {
            foreach (['bayan_id', 'saved', 'type_malaf', 'montant_rasemal_ijmali', 'montant_taawidat_youmiya', 'beneficiaires_json', 'masarif_janaza'] as $col) {
                if (Schema::hasColumn('dossiers', $col)) {
                    if ($col === 'bayan_id') {
                        $table->dropForeign(['bayan_id']);
                    }
                    $table->dropColumn($col);
                }
            }
        });
    }
};
