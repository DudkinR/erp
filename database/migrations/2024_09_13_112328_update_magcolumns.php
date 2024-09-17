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
        if (!Schema::hasColumn('magcolumns', 'dimensions')) {
            Schema::table('magcolumns', function (Blueprint $table) {
                $table->integer('dimensions')->nullable()->after('description');
            });
        }
        else {
            Schema::table('magcolumns', function (Blueprint $table) {
                $table->integer('dimensions')->nullable()->change();
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
