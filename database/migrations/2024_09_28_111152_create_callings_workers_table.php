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
        if (Schema::hasTable('callings_workers')) {
            return;
        }
        Schema::create('callings_workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calling_id')->constrained();
            // personal__id
            $table->foreignId('personal_id')->constrained();
            // type of worker type_id
            $table->foreignId('worker_type_id')->constrained();
            // type of payment type_id
            $table->foreignId('payment_type_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('callings_workers')) {
            return;
        }
        Schema::dropIfExists('callings_workers');
    }
};
