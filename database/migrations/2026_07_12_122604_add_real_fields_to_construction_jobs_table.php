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
        Schema::table('construction_jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('construction_jobs', 'real_whh')) {
                $table->float('real_whh')->nullable()->after('whh');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_jan')) {
                $table->float('real_jan')->nullable()->after('jan');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_feb')) {
                $table->float('real_feb')->nullable()->after('feb');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_mar')) {
                $table->float('real_mar')->nullable()->after('mar');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_apr')) {              
                $table->float('real_apr')->nullable()->after('apr');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_may')) {
                $table->float('real_may')->nullable()->after('may');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_jun')) {
                $table->float('real_jun')->nullable()->after('jun');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_jul')) {
                $table->float('real_jul')->nullable()->after('jul');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_aug')) {
                $table->float('real_aug')->nullable()->after('aug');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_sep')) {
                $table->float('real_sep')->nullable()->after('sep');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_oct')) {
                $table->float('real_oct')->nullable()->after('oct');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_nov')) {
                $table->float('real_nov')->nullable()->after('nov');
            }
            if (!Schema::hasColumn('construction_jobs', 'real_dec')) {
                $table->float('real_dec')->nullable()->after('dec');
            }
        });         
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('construction_jobs', function (Blueprint $table) {
        if(Schema::hasColumn('construction_jobs', 'real_whh')) {
                $table->dropColumn('real_whh');
            }
            if(Schema::hasColumn('construction_jobs', 'real_jan')) {
                $table->dropColumn('real_jan');
            }
            if(Schema::hasColumn('construction_jobs', 'real_feb')) {
                $table->dropColumn('real_feb');
            }
            if(Schema::hasColumn('construction_jobs', 'real_mar')) {
                $table->dropColumn('real_mar');
            }
            if(Schema::hasColumn('construction_jobs', 'real_apr')) {
                $table->dropColumn('real_apr');
            }
            if(Schema::hasColumn('construction_jobs', 'real_may')) {
                $table->dropColumn('real_may');
            }
            if(Schema::hasColumn('construction_jobs', 'real_jun')) {
                $table->dropColumn('real_jun');
            }
            if(Schema::hasColumn('construction_jobs', 'real_jul')) {
                $table->dropColumn('real_jul');
            }
            if(Schema::hasColumn('construction_jobs', 'real_aug')) {
                $table->dropColumn('real_aug');
            }
            if(Schema::hasColumn('construction_jobs', 'real_sep')) {
                $table->dropColumn('real_sep');
            }
            if(Schema::hasColumn('construction_jobs', 'real_oct')) {
                $table->dropColumn('real_oct');
            }
            if(Schema::hasColumn('construction_jobs', 'real_nov')) {
                $table->dropColumn('real_nov');
            }
            if(Schema::hasColumn('construction_jobs', 'real_dec')) {
                $table->dropColumn('real_dec');
            }
        });
    }
};
