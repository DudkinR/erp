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
        //after('status') add column 'picture'
        if(!Schema::hasColumn('calling', 'picture')) {
            Schema::table('calling', function (Blueprint $table) {
                $table->string('picture')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if(Schema::hasColumn('calling', 'picture')) {
            Schema::table('calling', function (Blueprint $table) {
                $table->dropColumn('picture');
            });
        }
    }
};
