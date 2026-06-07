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
     
        // Таблиця точок (об'єкти на карті)
        Schema::create('objects', function (Blueprint $table) {
            $table->id();
            $table->string('name');   // назва точки (АПК, НТЦ, УКБ)
            $table->integer('x');     // координата X на PNG
            $table->integer('y');     // координата Y на PNG
            $table->timestamps();
        });
        Schema::create('taxiroutes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_id'); // точка відправлення
            $table->unsignedBigInteger('to_id');   // точка призначення
            $table->date('date');                  // дата рейсу
            $table->time('time');                  // час рейсу
            $table->unsignedBigInteger('car_id');  // машина
            $table->timestamps();
            $table->foreign('from_id')->references('id')->on('objects')->onDelete('cascade');
            $table->foreign('to_id')->references('id')->on('objects')->onDelete('cascade');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
        });
        Schema::create('passenger_taxiroutes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxiroute_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->default('заброньовано'); // статус пасажира
            $table->timestamps();
            $table->foreign('taxiroute_id')->references('id')->on('taxiroutes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    { 
        Schema::dropIfExists('passenger_taxiroutes');
        Schema::dropIfExists('taxiroutes');
        Schema::dropIfExists('objects');
    }
};
