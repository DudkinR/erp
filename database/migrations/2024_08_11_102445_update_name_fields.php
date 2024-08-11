<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // найти все таблицы в базе данных с полями name и типом varchar и изменить их на текст
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $table = get_object_vars($table);
            $table = array_values($table)[0];
            $columns = DB::select("SHOW COLUMNS FROM $table");
            foreach ($columns as $column) {
                $column = get_object_vars($column);
                $column = array_values($column)[0];
                if ($column == 'name') {
                    DB::statement("ALTER TABLE $table MODIFY name TEXT");
                }
            }
        }   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
