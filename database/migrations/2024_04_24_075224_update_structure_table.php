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
        // update structure table if has not column 'status', 'kod' and 'parent_id'
        if (!Schema::hasColumn('structure', 'status')) {
            Schema::table('structure', function (Blueprint $table) {
                $table->string('status')->default('active');
            });
        }
        if (!Schema::hasColumn('structure', 'kod')) {
            Schema::table('structure', function (Blueprint $table) {
                $table->string('kod')->nullable();
            });
        }
        if (!Schema::hasColumn('structure', 'parent_id')) {
            Schema::table('structure', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_id')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // if has column 'status', 'kod' and 'parent_id' then drop it
        if (Schema::hasColumn('structure', 'status')) {
            Schema::table('structure', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
        if (Schema::hasColumn('structure', 'kod')) {
            Schema::table('structure', function (Blueprint $table) {
                $table->dropColumn('kod');
            });
        }
        if (Schema::hasColumn('structure', 'parent_id')) {
            Schema::table('structure', function (Blueprint $table) {
                $table->dropColumn('parent_id');
            });
        }
        

    }
};
