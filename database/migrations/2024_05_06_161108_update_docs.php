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
        // add col puth to docs
        if (!Schema::hasColumn('documentations', 'path')) {
            Schema::table('documentations', function (Blueprint $table) {
                $table->string('path')->nullable()->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('documentations', 'path')) {
            Schema::table('documentations', function (Blueprint $table) {
                $table->dropColumn('path');
            });
        }
    }
};
