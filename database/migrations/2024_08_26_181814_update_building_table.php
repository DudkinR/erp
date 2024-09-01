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
        //
        if( !Schema::hasTable('building') ) {
            Schema::create('building', function (Blueprint $table) {
                $table->id();
                $table->string('IDBuilding')->nullable();
                $table->string('name');
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('zip')->nullable();
                $table->string('country')->nullable();
                $table->string('abv')->nullable();
                $table->string('slug')->nullable();
                $table->string('organization')->nullable();
                // status = 0: inactive, 1: active, 2: rented 
                $table->Integer('status')->default(1);
                $table->text('image')->nullable();
                $table->timestamps();
            });
        }
        if(!Schema::hasColumn('building', 'IDBuilding')) {
            Schema::table('building', function (Blueprint $table) {
                $table->string('IDBuilding')->nullable()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if(Schema::hasColumn('building', 'IDBuilding')) {
            Schema::table('building', function (Blueprint $table) {
                $table->dropColumn('IDBuilding');
            });
        }
    }
};
