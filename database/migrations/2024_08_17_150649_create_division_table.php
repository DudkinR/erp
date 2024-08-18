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
        if(Schema::hasTable('division')) {
            return;
        }
        Schema::create('division', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description');
            // abv
            $table->string('abv');
            // slug
            $table->string('slug');
            // parent_id or 0 nullable
            $table->integer('parent_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division');
    }
};
