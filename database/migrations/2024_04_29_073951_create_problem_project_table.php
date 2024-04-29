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
    {// in table problems add columns project_id , stage_id ,step_id, control_id
        Schema::table('problems', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable()->after('id');
            $table->unsignedBigInteger('stage_id')->nullable()->after('project_id');
            $table->unsignedBigInteger('step_id')->nullable()->after('stage_id');
            $table->unsignedBigInteger('control_id')->nullable()->after('step_id');
            // status column
            $table->enum('status', ['new', 'in_progress', 'done', 'closed'])->default('new')->after('deadline');
        });
        // add table problem_personal 
        if (!Schema::hasTable('problem_personal')) {
            Schema::create('problem_personal', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('problem_id')->constrained();
                $table->unsignedBigInteger('personal_id')->constrained();
                // view column
                $table->boolean('view')->default(false);
                // comment column
                $table->text('comment')->nullable();
                $table->timestamps();
            });
        }

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('problems', function (Blueprint $table) {
            $table->dropColumn('project_id');
            $table->dropColumn('stage_id');
            $table->dropColumn('step_id');
            $table->dropColumn('control_id');
            $table->dropColumn('status');
        });
        if (!Schema::hasTable('problem_personal')) {
            return;
        }
        Schema::dropIfExists('problem_personal');
    }
};
