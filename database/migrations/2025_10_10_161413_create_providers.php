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
        if (!Schema::hasTable('providers')) {   
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('short_name')->nullable();
            $table->string('ownership_form')->nullable();
            $table->string('edrpou_code')->nullable();
            $table->string('country')->nullable();
            $table->text('products_services')->nullable();
            $table->string('decision_number')->nullable();
            $table->date('decision_date')->nullable();
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps(); // created_at та updated_at
        });
    }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('providers')) {
            Schema::dropIfExists('providers');
        }
    }
};
