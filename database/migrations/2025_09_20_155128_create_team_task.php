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
        if (Schema::hasTable('team_task')) {
            return;
        }
        Schema::create('team_task', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('creator_id'); // хто створив
            $table->unsignedBigInteger('assignee_id')->nullable(); // призначений виконавець
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['once','template','daily','weekly','monthly','yearly','custom'])->default('once');
            $table->json('recurrence')->nullable(); // { "interval":1, "days":[1,3], "rrule":"..." } для custom
            $table->timestamp('start_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('next_run_at')->nullable(); // для повторюваних — наступна інстанція
            $table->enum('status',['pending','in_progress','completed','cancelled'])->default('pending');
            $table->unsignedBigInteger('parent_task_id')->nullable(); // посилання на шаблон, якщо інстанція
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assignee_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('team_task')) {
            return;
        }
        Schema::dropIfExists('team_task');
    }
};
