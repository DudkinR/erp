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
        if (Schema::hasColumn('epm', 'divition')) {
            return;
        }
        //'epm',  add column integer 'area' to the table  after 'description' wanoarea.id
        Schema::table('epm', function (Blueprint $table) {
            $table->integer('divition')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (!Schema::hasColumn('epm', 'divition')) {
            return;
        }
        Schema::table('epm', function (Blueprint $table) {
            $table->dropColumn('divition');
        });
    }
};
