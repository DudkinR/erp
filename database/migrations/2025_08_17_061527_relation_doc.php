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
        /* окрему таблицю document_relations з полями:
        id
        document_id (старий документ)
        relation_type (replaced_by, canceled_by)
        related_document_id (новий документ або рішення/акт, яким анульовано)
        */
        if (!Schema::hasTable('document_relations')) {
            Schema::create('document_relations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('document_id')->constrained('adocuments')->onDelete('cascade');
                $table->enum('relation_type', ['replaced_by', 'canceled_by']);
                $table->foreignId('related_document_id')->constrained('adocuments')->onDelete('cascade');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('dump_document_relations')) {
            Schema::create('dump_document_relations', function (Blueprint $table) {
                $table->id();
                 $table->datetime('damp_date'); // Дата дампа
                $table->foreignId('document_id')->constrained('damp_adocuments')->onDelete('cascade');
                $table->enum('relation_type', ['replaced_by', 'canceled_by']);
                $table->foreignId('related_document_id')->constrained('damp_adocuments')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('document_relations');
        Schema::dropIfExists('dump_document_relations');
    }
};
