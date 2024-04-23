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
        if (Schema::hasTable('documentations')) {
            return;
        }
        Schema::create('documentations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            //дата пересмотра 
            $table->date('revision_date')->nullable();
            //дата публикации
            $table->date('publication_date')->nullable();
            //дата создания
            $table->date('creation_date')->nullable();
            //дата удаления
            $table->date('deletion_date')->nullable();
            //дата последнего изменения
            $table->date('last_change_date')->nullable();
            //дата последнего просмотра
            $table->date('last_view_date')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentations');
    }
};
