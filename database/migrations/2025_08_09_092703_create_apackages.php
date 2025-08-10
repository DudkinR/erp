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
          if (!Schema::hasTable('apackages')) {           
            Schema::create('apackages', function (Blueprint $table) {
                $table->id();                
                $table->string('foreign_name')->nullable(); // Наименование документа
                $table->string('national_name')->nullable(); // Можеш додати, якщо є український варіант
                $table->timestamps();
            });
        }
         if (!Schema::hasTable('adocuments')) {   
            Schema::create('adocuments', function (Blueprint $table) {
                $table->id();
                $table->string('foreign_name')->nullable(); // NAME
                $table->string('national_name')->nullable(); // Альтернативна назва
                $table->date('reg_date')->nullable();                 
                $table->date('production_date')->nullable(); // Дата в виробництво                
                $table->string('kor')->nullable(); //Корреспондент	
                 $table->string('part')->nullable(); //Часть_проекта	
                 $table->string('contract')->nullable(); //Ndog	
                $table->string('develop')->nullable(); //krat_neim	
                $table->string('object')->nullable(); //Obekt	
                $table->string('unit')->nullable(); //Блок	
                $table->string('stage')->nullable(); //Стадия_проекта	
               $table->string('code')->nullable();  //Шифр (обозначение документа)	
                $table->string('inventory')->nullable(); //Инв_№разраб_пр
               $table->string('path')->nullable();  // file puth
                $table->string('storage_location')->nullable(); // Місце зберігання            
                $table->timestamps();
            });
        }
         if (!Schema::hasTable('adocument_apackage'))
            Schema::create('adocument_apackage', function (Blueprint $table) {
                $table->id();
                $table->foreignId('adocument_id')->constrained()->onDelete('cascade');
                $table->foreignId('apackage_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adocument_apackage');
        Schema::dropIfExists('adocuments');
        Schema::dropIfExists('apackages');
    }
};
