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
        if (!Schema::hasTable('archive')) {
            Schema::create('archive', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Name of the document
                $table->string('description'); // Description of the document
                $table->string('retention_period')->nullable();
                $table->unsignedBigInteger('added_by');
                $table->timestamps();
                $table->foreign('added_by')->references('id')->on('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive');
    }
};
