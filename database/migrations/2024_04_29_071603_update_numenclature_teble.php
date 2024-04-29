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
        // nomenclature
        if(!Schema::hasColumn('nomenclature', 'type_id')) {
            Schema::table('nomenclature', function (Blueprint $table) {
                $table->unsignedBigInteger('type_id')->nullable();
                $table->foreign('type_id')->references('id')->on('types');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('nomenclature', 'type_id')) {
            Schema::table('nomenclature', function (Blueprint $table) {
                $table->dropForeign(['type_id']);
                $table->dropColumn('type_id');
            });
        }   
    }
};
