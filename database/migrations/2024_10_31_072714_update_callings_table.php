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
        if(!Schema::hasColumn('calling', 'author_id')){
            Schema::table('calling', function (Blueprint $table) {
                $table->integer('author_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calling', function (Blueprint $table) {
            $table->dropColumn('author_id');
        });
    }
};
