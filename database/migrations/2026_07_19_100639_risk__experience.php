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
        if (!Schema::hasTable('experience_risk')) {
            Schema::create('experience_risk', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('experience_id');
                $table->unsignedBigInteger('risk_id');
                $table->timestamps();

                $table->foreign('experience_id')->references('id')->on('experiences')->onDelete('cascade');
                $table->foreign('risk_id')->references('id')->on('risks')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('experience_risk')) {
            Schema::table('experience_risk', function (Blueprint $table) {
                $table->dropForeign(['experience_id']);
                $table->dropForeign(['risk_id']);
            });
            Schema::dropIfExists('experience_risk');
        }
    }
};
