<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        if (!Schema::hasTable('action_efficiencies')) {
            Schema::create('action_efficiencies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('action_id')->constrained('actions')->onDelete('cascade');
                $table->foreignId('criterion_id')->constrained('efficiency_criteria')->onDelete('cascade');
                $table->decimal('value_before', 10, 2)->nullable();
                $table->decimal('value_after', 10, 2)->nullable();
                $table->decimal('efficiency_index', 10, 2)->nullable();
                $table->text('comment')->nullable();    
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('action_efficiencies')) {
            Schema::dropIfExists('action_efficiencies');
        }
    }
};
