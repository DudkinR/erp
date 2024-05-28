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
        if (!Schema::hasTable('stores')) {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('IDname')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        }); 
        }
        if (!Schema::hasTable('products')) {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('IDname')->nullable();            
            $table->string('name');
            $table->text('description')->nullable();
            // дата изготовления
            $table->date('manufacture_date')->nullable();
            // дата окончания срока годности
            $table->date('expiration_date')->nullable();
            // дата поверки 
            $table->date('verification_date')->nullable();
            // дата последней проверки ТО
            $table->date('last_verification_date')->nullable();
            // дата следующей проверки ТО
            $table->date('next_verification_date')->nullable();
            // поставлен по проекту
            $table->integer('project')->nullable();
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('equipments')) {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('IDname')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            // дата изготовления
            $table->date('manufacture_date')->nullable();
            // дата окончания срока годности
            $table->date('expiration_date')->nullable();
            // дата поверки 
            $table->date('verification_date')->nullable();
            // дата последней проверки ТО
            $table->date('last_verification_date')->nullable();
            // дата следующей проверки ТО
            $table->date('next_verification_date')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
        }
        if (!Schema::hasTable('rooms')) {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('IDname')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('rooms')) {
            Schema::dropIfExists('rooms');
        }
        if (Schema::hasTable('equipments')) {
            Schema::dropIfExists('equipments');
        }
        if (Schema::hasTable('products')) {
            Schema::dropIfExists('products');
        }
        if (Schema::hasTable('stores')) {
        Schema::dropIfExists('stores');
        }
    }
};
