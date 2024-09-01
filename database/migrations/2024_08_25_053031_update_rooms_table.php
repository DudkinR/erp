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
        // if has no building_id
        if (!Schema::hasColumn('rooms', 'building_id')) {
            Schema::table('rooms', function (Blueprint $table) {
                $table->foreignId('building_id')->constrained('building')->onDelete('cascade');
                // Кат.ПБ	ЗСР	Підрозділ-власник	Дільниця
                $table->string('category_PB')->nullable();
                $table->boolean('RadiationSafetyZone')->nullable();
                $table->foreignId('owner_division')->nullable()->constrained('divisions');
                $table->foreignId('owner_subdivision')->nullable()->constrained('divisions');

            });
        }   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'building_id')) {
                // Замініть 'rooms_building_id_foreign' на правильне ім'я зовнішнього ключа, якщо потрібно
                $table->dropForeign(['building_id']);
                $table->dropColumn('building_id');
            }
    
            if (Schema::hasColumn('rooms', 'category_PB')) {
                $table->dropColumn('category_PB');
            }
    
            if (Schema::hasColumn('rooms', 'RadiationSafetyZone')) {
                $table->dropColumn('RadiationSafetyZone');
            }
    
            if (Schema::hasColumn('rooms', 'owner_division')) {
                // Замініть 'rooms_owner_division_foreign' на правильне ім'я зовнішнього ключа, якщо потрібно
                $table->dropForeign(['owner_division']);
                $table->dropColumn('owner_division');
            }
    
            if (Schema::hasColumn('rooms', 'owner_subdivision')) {
                // Замініть 'rooms_owner_subdivision_foreign' на правильне ім'я зовнішнього ключа, якщо потрібно
                $table->dropForeign(['owner_subdivision']);
                $table->dropColumn('owner_subdivision');
            }
        });
    }
    
};
