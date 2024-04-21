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
        if (Schema::hasTable('facts')) {
            return;
        }
        Schema::create('facts', function (Blueprint $table) {
            $table->id();
            // name
            $table->string('name');
            // description
            $table->text('description');
            // image
            $table->string('image');
            // status 
            $table->enum('status', [
                'active', 
                'freeze',
                'inactive',
                'completed',
                'closed'
                ])->default('active');           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('facts')) {
            return;
        }   
        Schema::dropIfExists('facts');
    }
};
