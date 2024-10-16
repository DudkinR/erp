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
        // callings_workers not has col comment
        if (Schema::hasTable('callings_workers') && !Schema::hasColumn('callings_workers', 'comment')) {
            Schema::table('callings_workers', function (Blueprint $table) {
                $table->text('comment')->nullable()->after('payment_type_id');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasTable('callings_workers') && Schema::hasColumn('callings_workers', 'comment')) {
            Schema::table('callings_workers', function (Blueprint $table) {
                $table->dropColumn('comment');
            });
        }
    }
};
