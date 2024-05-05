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
        // add column `average_time` position_id parent_id  to table `dimensions`
        Schema::table('dimensions', function (Blueprint $table) {
            $table->string('average_time')->nullable()->after('default_unit');
            $table->unsignedBigInteger('position_id')->nullable()->after('average_time');
            $table->unsignedBigInteger('parent_id')->nullable()->default(0)->after('position_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('dimensions', function (Blueprint $table) {
            $table->dropColumn('average_time');
            $table->dropColumn('position_id');
            $table->dropColumn('parent_id');
        });
    }
};
