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
         if (!Schema::hasColumn('adocuments', 'archive_number')) {
            Schema::table('adocuments', function (Blueprint $table) {
                $table->string('archive_number')->nullable()->after('pages');
            });
        }
        if (!Schema::hasColumn('damp_adocuments', 'archive_number')) {
            Schema::table('damp_adocuments', function (Blueprint $table) {
                $table->string('archive_number')->nullable()->after('pages');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('adocuments', function (Blueprint $table) {
            $table->dropColumn('archive_number');
        });
        Schema::table('damp_adocuments', function (Blueprint $table) {
            $table->dropColumn('archive_number');
        });
    }
};
