<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure existing null or invalid values are set to a default value
        DB::table('magcolumns')
            ->whereNull('dimensions')
            ->orWhere('dimensions', 'NOT REGEXP', '^[0-9]+$')
            ->update(['dimensions' => 0]);

        if (!Schema::hasColumn('magcolumns', 'dimensions')) {
            Schema::table('magcolumns', function (Blueprint $table) {
                $table->integer('dimensions')
                    ->default(0)
                    ->after('description');
            });
        } else {
            Schema::table('magcolumns', function (Blueprint $table) {
                $table->integer('dimensions')
                    ->default(0)
                    ->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('magcolumns', 'dimensions')) {
            Schema::table('magcolumns', function (Blueprint $table) {
                $table->dropColumn('dimensions');
            });
        }
    }
};