<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pour bases déjà migrées avec l'ancienne version du schéma.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (! Schema::hasColumn('dossiers', 'nom_assurance_normalise')) {
                $table->string('nom_assurance_normalise')->nullable()->after('nom_assurance');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (Schema::hasColumn('dossiers', 'nom_assurance_normalise')) {
                $table->dropColumn('nom_assurance_normalise');
            }
        });
    }
};
