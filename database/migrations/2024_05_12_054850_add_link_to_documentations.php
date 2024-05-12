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
        Schema::table('documentations', function (Blueprint $table) {
            $table->string('link')->nullable()->after('path');
            // delete documentations_slug_unique index from documentations table
            $table->dropUnique('documentations_slug_unique');
            // slug  nullable
            $table->string('slug')->nullable()->change();
            // category_id  nullable
            $table->unsignedBigInteger('category_id')->nullable()->change();
            // add status  column to documentations table 1 -number of characters
            $table->string('status', 1)->nullable()->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documentations', function (Blueprint $table) {
            $table->dropColumn('link');
            
            // slug  not nullable
            $table->string('slug')->nullable(false)->change();
            // add documentations_slug_unique index to documentations table
            $table->unique('slug');
            // category_id  not nullable
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            // drop status  column from documentations table
            $table->dropColumn('status');
        });
    }
};
