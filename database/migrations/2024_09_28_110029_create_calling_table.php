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
        if (Schema::hasTable('calling')) {
            return;
        }
        Schema::create('calling', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            // vyklyk_na_robotu type id
            $table->integer('type_id')->nullable();
            // calling TIME
            $table->time('start_time')->nullable();
            // calling person
            $table->integer('personal_start_id')->nullable();
            // arrival TIME
            $table->time('arrival_time')->nullable();
            // arrival person
            $table->integer('personal_arrival_id')->nullable();
            // work TIME
            $table->time('work_time')->nullable();
            // work person
            $table->integer('personal_work_id')->nullable();
            // end TIME
            $table->time('end_time')->nullable();
            // end person
            $table->integer('personal_end_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('calling')) {
            return;
        }
        Schema::dropIfExists('calling');
    }
};
