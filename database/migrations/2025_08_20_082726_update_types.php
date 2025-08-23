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
         if (!Schema::hasColumn('types', 'foreing')) {
            Schema::table('types', function (Blueprint $table) {
                $table->string('foreing')->nullable()->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 
      if (Schema::hasColumn('types', 'foreing')) {
            $table->dropColumn('foreing');
        }
    }
};
