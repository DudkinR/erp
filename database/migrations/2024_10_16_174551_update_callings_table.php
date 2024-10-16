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
        // delete work_time column and personal_work_id column if has
         Schema::table('calling', function (Blueprint $table) {
            // update start_time column as 	timestamp	
            $table->timestamp('start_time')->nullable()->change();
            // arrival_time column as 	timestamp
            $table->timestamp('arrival_time')->nullable()->change();
            // end_time column as 	timestamp
            $table->timestamp('end_time')->nullable()->change();
                $table->dropColumn('work_time');
                $table->dropColumn('personal_work_id');
            });
   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('calling', function (Blueprint $table) {
            $table->timestamp('work_time')->nullable();
            $table->integer('personal_work_id')->nullable();
        });
   
    }
};
