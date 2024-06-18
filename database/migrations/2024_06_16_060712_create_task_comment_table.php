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
        if (!Schema::hasTable('task_comment')) {
            Schema::create('task_comment', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('task_id')->constrained();
                $table->bigInteger('comment_id')->constrained();
                $table->timestamps();
            });
        }
        // project_comment
        if (!Schema::hasTable('project_comment')) {
            Schema::create('project_comment', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('project_id')->constrained();
                $table->bigInteger('comment_id')->constrained();
                $table->timestamps();
            });
        }
        // client_comment
        if (!Schema::hasTable('client_comment')) {
            Schema::create('client_comment', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('client_id')->constrained();
                $table->bigInteger('comment_id')->constrained();
                $table->timestamps();
            });
        }
        // equipment_comment
        if (!Schema::hasTable('equipment_comment')) {
            Schema::create('equipment_comment', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('equipment_id')->constrained();
                $table->bigInteger('comment_id')->constrained();
                $table->timestamps();
            });
        }
        // doc_comment
        if (!Schema::hasTable('doc_comment')) {
            Schema::create('doc_comment', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('doc_id')->constrained();
                $table->bigInteger('comment_id')->constrained();
                $table->timestamps();
            });
        }
        // nomenclature_comment
        if (!Schema::hasTable('nomenclature_comment')) {
            Schema::create('nomenclature_comment', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('nomenclature_id')->constrained();
                $table->bigInteger('comment_id')->constrained();
                $table->timestamps();
            });
        }
        // problem_comment
        if (!Schema::hasTable('problem_comment')) {
            Schema::create('problem_comment', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('problem_id')->constrained();
                $table->bigInteger('comment_id')->constrained();
                $table->timestamps();
            });
        }
        // product
        if (!Schema::hasTable('product_comment')) {
            Schema::create('product_comment', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('product_id')->constrained();
                $table->bigInteger('comment_id')->constrained();
                $table->timestamps();
            });
        }
        // store_comment
        if (!Schema::hasTable('store_comment')) {
            Schema::create('store_comment', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('store_id')->constrained();
                $table->bigInteger('comment_id')->constrained();
                $table->timestamps();
            });
        }
        
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('task_comment')) {
            Schema::dropIfExists('task_comment');
        }
        if (Schema::hasTable('project_comment')) {
            Schema::dropIfExists('project_comment');
        }
        if (Schema::hasTable('client_comment')) {
            Schema::dropIfExists('client_comment');
        }
        if (Schema::hasTable('equipment_comment')) {
            Schema::dropIfExists('equipment_comment');
        }
        if (Schema::hasTable('doc_comment')) {
            Schema::dropIfExists('doc_comment');
        }
        if (Schema::hasTable('nomenclature_comment')) {
            Schema::dropIfExists('nomenclature_comment');
        }
        if (Schema::hasTable('problem_comment')) {
            Schema::dropIfExists('problem_comment');
        }
        if (Schema::hasTable('product_comment')) {
            Schema::dropIfExists('product_comment');
        }
        if (Schema::hasTable('store_comment')) {
            Schema::dropIfExists('store_comment');
        }      
    }
};
