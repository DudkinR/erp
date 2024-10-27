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
        if (Schema::hasTable('carorders')) {
            return;
        }
        Schema::create('carorders', function (Blueprint $table) {
            $table->id();
            // title
            $table->string('title');
            // description
            $table->text('description');
            // typecar_id
            $table->unsignedBigInteger('typecar_id')->nullable();
            $table->foreign('typecar_id')->references('id')->on('types');
            // value
            $table->integer('val')->nullable();
            // value_type_id 
            $table->unsignedBigInteger('value_type_id')->nullable();
            $table->foreign('value_type_id')->references('id')->on('types');
            // status
            $table->string('status')->nullable()->default('new');
            // start_datetime
            $table->dateTime('start_datetime');
            // end_datetime
            $table->dateTime('end_datetime');
            // division_id
            $table->unsignedBigInteger('division_id')->nullable();
            // start point googlemap
            $table->string('start_point')->nullable();
            // end point googlemap
            $table->string('end_point')->nullable();
            //hours
            $table->integer('hours');   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('carorders')) {
            return;
        }
        Schema::dropIfExists('carorders');
    }
};
