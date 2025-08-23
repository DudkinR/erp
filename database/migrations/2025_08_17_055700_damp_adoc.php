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
         if (!Schema::hasTable('damp_apackages')) {           
            Schema::create('damp_apackages', function (Blueprint $table) {
                $table->id(); 
                // data time of damp                
                $table->datetime('damp_date'); // Дата дампа       
                $table->bigInteger('id_npp')->unsigned()->nullable(); // ID NPP         
                $table->string('foreign_name')->nullable(); // Наименование документа
                $table->string('national_name')->nullable(); // Можеш додати, якщо є український варіант

                
            });
        }
         if (!Schema::hasTable('damp_adocuments')) {   
            Schema::create('damp_adocuments', function (Blueprint $table) {
                $table->id();
                $table->datetime('damp_date'); // Дата дампа
                $table->bigInteger('id_npp')->unsigned()->nullable(); // ID NPP
                $table->string('foreign_name')->nullable(); // NAME
                $table->string('national_name')->nullable(); // Альтернативна назва
                $table->integer('doc_type_id')->nullable();
                $table->date('reg_date')->nullable();     
                $table->integer('pages')->nullable(); // Кількість сторінок
                $table->date('production_date')->nullable(); // Дата в виробництво                
                $table->string('kor')->nullable(); //Корреспондент	
                 $table->string('part')->nullable(); //Часть_проекта	
                 $table->string('contract')->nullable(); //Ndog	
                $table->string('develop')->nullable(); //krat_neim	
                $table->string('object')->nullable(); //Obekt	
                $table->string('unit')->nullable(); //Блок	
                $table->string('stage')->nullable(); //Стадия_проекта	
               $table->string('code')->nullable();  //Шифр  документа (обозначение документа)	
                $table->string('inventory')->nullable(); //Инв_№разраб_пр
               $table->string('path')->nullable();  // file puth
                $table->string('storage_location')->nullable(); // Місце зберігання            
                $table->bigInteger('package_id')->unsigned()->nullable(); // ID пакета
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
        Schema::dropIfExists('damp_adocuments');
        Schema::dropIfExists('damp_apackages');
    }
};
