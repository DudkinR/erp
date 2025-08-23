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
        // doc_doc `id`, `doc_id`, `related_doc_id`, `type`, `created_at`, `updated_at`
        // add type doc
        if (!Schema::hasColumn('doc_doc', 'type')) {
            Schema::table('doc_doc', function (Blueprint $table) {
                $table->string('type')->default('D')->after('related_doc_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        if (Schema::hasColumn('doc_doc', 'type')) {
            Schema::table('doc_doc', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
