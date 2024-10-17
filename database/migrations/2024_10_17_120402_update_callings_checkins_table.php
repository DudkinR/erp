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
        if(!Schema::hasColumn('callings_checkins', 'type')) {
            Schema::table('callings_checkins', function (Blueprint $table) {
                $table->integer('type')->nullable()->after('personal_id');
                //comment 
                $table->text('comment')->nullable()->after('type');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       if(Schema::hasColumn('callings_checkins', 'type')) {
            Schema::table('callings_checkins', function (Blueprint $table) {
                $table->dropColumn('type');
                $table->dropColumn('comment');
            });
        }
    }
};
