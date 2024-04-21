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
        if (Schema::hasTable('criterias')) {
            return;
        }
        Schema::create('criterias', function (Blueprint $table) {
            $table->id();
            // name
            $table->string('name');
            // description
            $table->text('description');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('criterias')) {
            return;
        }
        Schema::dropIfExists('criterias');
    }
};
