<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // add columns "dimensions" to magcolumns table размерность
        if (!Schema::hasColumn('magcolumns', 'dimensions')) {
            Schema::table('magcolumns', function (Blueprint $table) {
                $table->json('dimensions')->nullable()->after('description');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasColumn('magcolumns', 'dimensions')) {
            Schema::table('magcolumns', function (Blueprint $table) {
                $table->dropColumn('dimensions');
            });
        }
    }
};
