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
        if (Schema::hasTable('maglimits')) {
            return;
        }
        /*
        high 
        low
        fixation 
        emergency
        reglementary
        working
        border
        */
        Schema::create('maglimits', function (Blueprint $table) {
            $table->id();
            $table->float('hfb')->nullable();
            $table->integer('hfb_doc_id')->nullable();
            $table->float('heb')->nullable();
            $table->integer('heb_doc_id')->nullable();
            $table->float('hrb')->nullable();
            $table->integer('hrb_doc_id')->nullable();
            $table->float('hwb')->nullable();
            $table->integer('hwb_doc_id')->nullable();
            $table->float('lwb')->nullable();
            $table->integer('lwb_doc_id')->nullable();
            $table->float('lrb')->nullable();
            $table->integer('lrb_doc_id')->nullable();
            $table->float('leb')->nullable();
            $table->integer('leb_doc_id')->nullable();
            $table->float('lfb')->nullable();
            $table->integer('lfb_doc_id')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('maglimits')) {
            return;
        }
        Schema::dropIfExists('maglimits');
    }
};
