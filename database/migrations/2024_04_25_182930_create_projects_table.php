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
        if (Schema::hasTable('projects')) {
            return;
        }
        Schema::create('projects', function (Blueprint $table) {
             $table->id();
            $table->string('name')->nullable(); 
            $table->text('description')->nullable();
            $table->integer('priority')->nullable();
            $table->string('number')->nullable();
            $table->date('date')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('client')->nullable();
            $table->string('current_state')->nullable();
            $table->date('execution_period')->nullable();
            $table->integer('payment_percentage')->nullable();
            $table->integer('shipping_percentage')->nullable();
            $table->integer('debt_percentage')->nullable();
            $table->string('currency')->nullable();
            $table->string('operation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('projects')) {
            return;
        }
        Schema::dropIfExists('projects');
    }
};
