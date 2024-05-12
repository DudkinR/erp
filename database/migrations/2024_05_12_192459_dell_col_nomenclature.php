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
        Schema::table('nomenclature', function (Blueprint $table) {
           // dell column image if has
            if (Schema::hasColumn('nomenclature', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('nomenclature', 'nomenclature_type_id_foreign')) {
                $table->dropColumn('nomenclature_type_id_foreign');
            }
            
            if (Schema::hasColumn('nomenclature', 'type_id')) {
                $table->dropColumn('type_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('nomenclature', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->unsignedBigInteger('type_id');
        });

    }
};
