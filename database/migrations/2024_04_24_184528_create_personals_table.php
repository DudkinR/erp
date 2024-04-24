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
        if (Schema::hasTable('personals')) {
            return;
        }
        Schema::create('personals', function (Blueprint $table) {
            $table->id();
            // tn
            $table->string('tn');
            // name
            $table->string('name');
            // surname
            $table->string('surname');
            // patronymic
            $table->string('patronymic');
            // short_name
            $table->string('short_name');
            // email
            $table->string('email');
            // phone
            $table->string('phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('personals')) {
            return;
        }
        Schema::dropIfExists('personals');
    }
};
