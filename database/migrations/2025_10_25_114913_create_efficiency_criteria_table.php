<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        if (!Schema::hasTable('efficiency_criteria')) {
            Schema::create('efficiency_criteria', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('proposal_id')->nullable();
                $table->unsignedBigInteger('action_id')->nullable();
                $table->string('name')->nullable();
                $table->decimal('weight', 5, 2)->nullable();
                $table->string('unit')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('efficiency_criteria')) {
            Schema::dropIfExists('efficiency_criteria');
        }
    }
};
