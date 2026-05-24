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
         if (Schema::hasTable('inconsistencs')) {
            return;
        }
        Schema::create('inconsistencs', function (Blueprint $table) {
            $table->id();

            // Основні поля невідповідності
            $table->string('point')->nullable();          // пункт документа
            $table->text('current_text')->nullable();     // поточна редакція
            $table->text('proposed_text')->nullable();    // нова редакція від юзера
            $table->text('reason')->nullable();           // чому не відповідає

            // Поля для служби якості
            $table->text('qa_text')->nullable();          // редакція від СЯ
            $table->boolean('qa_confirmation')->nullable(); // підтвердження доцільності

            // Поля для автора
            $table->boolean('is_fixed')->default(false);  // усунуто чи ні
           // $table->text('author_response')->nullable();  // пояснення автора

            // Статус життєвого циклу
            $table->string('status')->default('created');

            $table->timestamps();
        });
        // Зв'язок "невідповідність ↔ документ"
        Schema::create('document_inconsistency', function (Blueprint $table) {
            $table->id();
            $table->string('document_inv_no');
            $table->foreign('document_inv_no')
                  ->references('inv_no')
                  ->on('documents')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('inconsistency_id');
            $table->foreign('inconsistency_id')
                  ->references('id')
                  ->on('inconsistencs')
                  ->onDelete('cascade');

            $table->timestamps();
        });

        // Зв'язок "невідповідність ↔ користувачі"
        Schema::create('inconsistency_user', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('inconsistency_id');
            $table->foreign('inconsistency_id')
                  ->references('id')
                  ->on('inconsistencs')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->string('role')->nullable(); // 'creator', 'qa', 'author'

            $table->timestamps();
        });

         if (Schema::hasTable('author_responses')) {
            return;
        }

        Schema::create('author_responses', function (Blueprint $table) {
            $table->id();

            // Зв'язок з невідповідністю
            $table->unsignedBigInteger('inconsistency_id');
            $table->foreign('inconsistency_id')
                  ->references('id')
                  ->on('inconsistencs')
                  ->onDelete('cascade');

            // Хто залишив відповідь (автор документа)
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // Текст пояснення
            $table->text('response_text');

            // Чи це усунення, чи обґрунтування
            $table->boolean('is_fixed')->default(false);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inconsistency_user');
        Schema::dropIfExists('document_inconsistency');
         Schema::dropIfExists('author_responses');
        Schema::dropIfExists('inconsistencs');
    }
};
