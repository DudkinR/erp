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
        if (Schema::hasTable('briefs')) {
            return;
        }
        Schema::create('briefs', function (Blueprint $table) {
            $table->id();
            $table->text('name_uk');
            $table->text('name_ru')->nullable();          
            $table->text('name_en')->nullable();
            //order
            $table->integer('order')->default(0);
            // type of brief
            $table->integer('type')->default(0);
            // risk 0-24
            $table->integer('risk')->default(0);
            // functional
            $table->integer('functional')->default(0);          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('briefs')) {
            return;
        }
        Schema::dropIfExists('briefs');
    }
};
