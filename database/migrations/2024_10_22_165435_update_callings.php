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
        // add new columns status
        if(!Schema::hasColumn('calling', 'status')) {
            Schema::table('calling', function (Blueprint $table) {
                $table->string('status')->default('created')->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if(Schema::hasColumn('calling', 'status')) {
            Schema::table('calling', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
