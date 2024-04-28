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
        if (Schema::hasTable('acts')) {
            return;
        }
        Schema::create('acts', function (Blueprint $table) {
            $table->id();
            // name
            $table->string('name');
            // description
            $table->text('description')->nullable();
            // complite date 
            $table->date('complite_date');
            // dedline date
            $table->date('dedline_date');
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
        if (!Schema::hasTable('acts')) {
            return;
        }
        Schema::dropIfExists('acts');
    }
};
