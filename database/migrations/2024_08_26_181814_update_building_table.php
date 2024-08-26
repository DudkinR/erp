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
        //
        if(!Schema::hasColumn('building', 'IDBuilding')) {
            Schema::table('building', function (Blueprint $table) {
                $table->string('IDBuilding')->nullable()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if(Schema::hasColumn('building', 'IDBuilding')) {
            Schema::table('building', function (Blueprint $table) {
                $table->dropColumn('IDBuilding');
            });
        }
    }
};
