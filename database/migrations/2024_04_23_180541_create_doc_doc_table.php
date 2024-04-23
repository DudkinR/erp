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
        if (Schema::hasTable('doc_doc')) {
            return;
        }
        Schema::create('doc_doc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doc_id');
            $table->unsignedBigInteger('related_doc_id');
            $table->timestamps();           

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('doc_doc')) {
            return;
        }
        Schema::dropIfExists('doc_doc');
    }
};
