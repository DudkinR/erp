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
        if (Schema::hasTable('control_doc')) {
            return;
        }
        Schema::create('control_doc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('control_id')->constrained();
            $table->foreignId('doc_id')->constrained('documentations');
            $table->string('status')->nullable()->default('active');
            $table->string('link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('control_doc')) {
            return;
        }
        Schema::dropIfExists('control_doc');
    }
};
