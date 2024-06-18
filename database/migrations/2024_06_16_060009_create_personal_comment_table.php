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
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->text('comment')->nullable();
                $table->timestamps();
            });
        }
        if(!Schema::hasTable('personal_comment')){
            Schema::create('personal_comment', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('personal_id')->constrained();
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
        if (Schema::hasTable('comments')) {
            Schema::dropIfExists('comments');
        }
        if(Schema::hasTable('personal_comment')){
        Schema::dropIfExists('personal_comment');
        }
    }
};
