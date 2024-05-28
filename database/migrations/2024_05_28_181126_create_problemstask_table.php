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
        // problems table  add column task_id
      if (!Schema::hasColumn('problems', 'task_id')) {
            Schema::table('problems', function (Blueprint $table) {
                $table->unsignedBigInteger('task_id')->nullable()->after('step_id'); 
                $table->unsignedBigInteger('user_id')->nullable()->after('task_id');
                $table->unsignedBigInteger('responsible_position_id')->nullable()->after('user_id');
                // chenge column status(enum) to status (string)
                $table->string('status')->change();
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('problems', 'task_id')) {
            Schema::table('problems', function (Blueprint $table) {
                $table->dropColumn('task_id');
                $table->dropColumn('user_id');
                $table->dropColumn('responsible_position_id');
                
            });
        }
       
    }
};
