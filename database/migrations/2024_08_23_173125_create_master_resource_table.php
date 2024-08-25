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
        if (!Schema::hasTable('master_resource')) {
            Schema::create('master_resource', function (Blueprint $table) {
                $table->id();
                $table->foreignId('master_id')->constrained('master');
                $table->foreignId('resource_id')->constrained('resource');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_resource');
    }
};
