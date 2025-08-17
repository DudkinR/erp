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
        
        // add column pages
        if (Schema::hasColumn('adocuments', 'pages')) {
            return;
        }
        Schema::table('adocuments', function (Blueprint $table) {
            $table->integer('pages')->default(0)->after('reg_date');
        });       


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('adocuments', 'pages')) {
         Schema::table('adocuments', function (Blueprint $table) {
                $table->dropColumn('pages');
            });
        }
    }
};
