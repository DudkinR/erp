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
        // add column min and max to epm table
        if (Schema::hasColumn('epm', 'min')) {
            return;
        }
        Schema::table('epm', function (Blueprint $table) {
            $table->integer('min')->default(0)->after('area');
            $table->integer('max')->default(0)->after('min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasColumn('epm', 'min')) {
            Schema::table('epm', function (Blueprint $table) {
                $table->dropColumn('min');
                $table->dropColumn('max');
            });
        }
    }
};
