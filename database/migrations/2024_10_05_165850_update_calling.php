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
        if (Schema::hasTable('calling') && !Schema::hasColumn('calling', 'type_id')) {
            Schema::table('calling', function (Blueprint $table) {
               // add column type tyniint to calling table name is type_id
                $table->tinyInteger('type_id')->nullable()->after('description');

            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // drop column type_id from calling table
        if (Schema::hasTable('calling') && Schema::hasColumn('calling', 'type_id')) {
        Schema::table('calling', function (Blueprint $table) {
            $table->dropColumn('type_id');
        });
        }

    }
};
