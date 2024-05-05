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
        if (!Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                // project id
                $table->unsignedBigInteger('project_id');
                // stage id
                $table->unsignedBigInteger('stage_id')->nullable();
                // step id
                $table->unsignedBigInteger('step_id')->nullable();
                // dimension id
                $table->unsignedBigInteger('dimension_id')->nullable();
                // control id
                $table->unsignedBigInteger('control_id')->nullable();
                //deadline date
                $table->date( 'deadline_date')->nullable();
                // status
                $table->string('status')->nullable()->default('active');
                // responsible position id
                $table->unsignedBigInteger('responsible_position_id')->nullable();
                // dependent task id
                $table->unsignedBigInteger('dependent_task_id')->nullable()->default(0);
                // parent task id
                $table->unsignedBigInteger('parent_task_id')->nullable()->default(0);
                // real start date
                $table->date('real_start_date')->nullable();
                // real end date
                $table->date('real_end_date')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        if (Schema::hasTable('tasks')) {
            Schema::dropIfExists('tasks');
        }
    }
};
