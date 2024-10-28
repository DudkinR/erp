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
        //add 'start_time', 'end_time'
        if(!Schema::hasColumn('callings_workers', 'start_time')){
            Schema::table('callings_workers', function (Blueprint $table) {
                $table->timestamp('start_time')->nullable();
                $table->timestamp('end_time')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if(Schema::hasColumn('callings_workers', 'start_time')){
            Schema::table('callings_workers', function (Blueprint $table) {
                $table->dropColumn('start_time');
                $table->dropColumn('end_time');
            });
        }
    }
};
