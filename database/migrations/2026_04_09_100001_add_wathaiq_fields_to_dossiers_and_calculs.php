<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
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
}
};
