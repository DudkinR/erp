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
        if (Schema::hasTable('epmdata')) {
            return;
        }
        Schema::create('epmdata', function (Blueprint $table) {
            $table->id();
            // epm_id, value, date_received, date_entered, blocked, user_id
            $table->foreignId('epm_id')->constrained('epm');
            $table->integer('value')->nullable(); 
            $table->date('date_received')->nullable();
            $table->date('date_entered')->nullable();
            $table->boolean('blocked')->default(false);
            $table->foreignId('user_id')->constrained('users')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(!Schema::hasTable('epmdata')) {
            return;
        }
        Schema::dropIfExists('epmdata');
    }
};
