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
        // if 'adocuments' hasn't column status
        if (!Schema::hasColumn('adocuments', 'status')) {
            Schema::table('adocuments', function (Blueprint $table) {
                /*  enum status {
                    active – чинний документ;
                    canceled – анульований;
                    replaced – замінений іншим документом;
                    draft – проект;
                    за потреби — інші проміжні статуси.
                }*/
                $table->enum('status', [
                    'active',
                    'canceled',
                    'replaced',
                    'draft',
                    'other'
                ])->default('draft');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasColumn('adocuments', 'status')) {
            Schema::table('adocuments', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
