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
        //table rooms delete colmbn address, add column square, floor 
        if (!Schema::hasColumn('rooms', 'address')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->string('address')->nullable();
            });
        }
            if (!Schema::hasColumn('rooms', 'square')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->dropColumn('square');
                });
            }
        if (!Schema::hasColumn('rooms', 'floor')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->dropColumn('floor');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

        Schema::table('rooms', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->dropColumn('square');
            $table->dropColumn('floor');
        });
    }
};
