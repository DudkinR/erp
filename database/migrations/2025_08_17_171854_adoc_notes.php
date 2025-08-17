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
         // add column pages
        if (!Schema::hasColumn('adocuments', 'notes')) {
            Schema::table('adocuments', function (Blueprint $table) {
                $table->string('notes')->nullable()->after('pages');
            });
        }
        if (!Schema::hasColumn('damp_adocuments', 'notes')) {
            Schema::table('damp_adocuments', function (Blueprint $table) {
                $table->string('notes')->nullable()->after('pages');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adocuments', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
        Schema::table('damp_adocuments', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
