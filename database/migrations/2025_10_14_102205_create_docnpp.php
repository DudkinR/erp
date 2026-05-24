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
         if (Schema::hasTable('docnpp')) {
            return;
        }
        Schema::create('docnpp', function (Blueprint $table) {
           $table->id();
            $table->string('document_type')->nullable();
            $table->string('code')->nullable();
            $table->string('organization')->nullable();
            $table->string('inventory_number')->nullable();
            $table->text('summary')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->text('distribution')->nullable();
            $table->string('replaces')->nullable();
            $table->string('replaced_by')->nullable();
            $table->string('change_number')->nullable();
            $table->integer('page_count')->nullable();
            $table->text('note')->nullable();
            $table->string('registration_place')->nullable();
            $table->date('registration_date')->nullable();
            $table->boolean('is_canceled')->default(false);
            $table->date('cancellation_date')->nullable();
            $table->boolean('implemented')->default(false);
            $table->string('author')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('project')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docnpp');
    }
};
