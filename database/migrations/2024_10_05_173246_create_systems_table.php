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
        if (Schema::hasTable('systems')) {
            return;
        }
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string('uk')->nullable();
            $table->string('ru')->nullable();
            $table->string('en')->nullable();
            $table->string('abv')->nullable();
            $table->string('group')->nullable();
            $table->string('svb')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('systems')) {
            return;
        }
        Schema::dropIfExists('systems');
    }
};
