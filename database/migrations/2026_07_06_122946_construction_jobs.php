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
        if (!Schema::hasTable('construction_jobs')) { 
            Schema::create('construction_jobs', function (Blueprint $table) {
                $table->id();                
                
                // Основні текстові та категоріальні поля з індексами для швидкої фільтрації
                $table->text('basis')->nullable()->comment('Підрозділ / підстава');
                $table->string('build')->nullable()->comment('Назва будівлі (споруди)');
                $table->string('room')->nullable()->comment('Номер приміщення');
                $table->string('location_axes')->nullable()->comment('Ряд, вісь, відмітка');
                $table->string('element')->nullable()->comment('Елемент ремонту');
                
                // Короткий опис робіт (залишаємо text, бо опис буває довгим)
                $table->text('work_type')->nullable()->comment('Короткий опис робіт');
                $table->string('unit')->nullable()->comment('Одиниці виміру (напр. м2)');
                
                // Числові показники (змінено з string на decimal для можливості математичних розрахунків)
                $table->decimal('q', 12, 2)->nullable()->comment('Кількість');
                $table->decimal('whh', 12, 2)->nullable()->comment('Трудовитрати, люд / год');
                
                $table->string('type')->nullable()->comment('Тип: ОЗП, ППР-1, ППР-2 тощо');
                $table->smallInteger('year')->nullable()->comment('Рік');
               // Розподіл трудовитрат по всіх 12 місяцях
                $table->decimal('jan', 12, 2)->nullable()->comment('Січень, люд / год');
                $table->decimal('feb', 12, 2)->nullable()->comment('Лютий, люд / год');
                $table->decimal('mar', 12, 2)->nullable()->comment('Березень, люд / год');
                $table->decimal('apr', 12, 2)->nullable()->comment('Квітень, люд / год');
                $table->decimal('may', 12, 2)->nullable()->comment('Травень, люд / год');
                $table->decimal('jun', 12, 2)->nullable()->comment('Червень, люд / год');
                $table->decimal('jul', 12, 2)->nullable()->comment('Липень, люд / год');
                $table->decimal('aug', 12, 2)->nullable()->comment('Серпень, люд / год');
                $table->decimal('sep', 12, 2)->nullable()->comment('Вересень, люд / год');
                $table->decimal('oct', 12, 2)->nullable()->comment('Жовтень, люд / год');
                $table->decimal('nov', 12, 2)->nullable()->comment('Листопад, люд / год');
                $table->decimal('dec', 12, 2)->nullable()->comment('Грудень, люд / год');
                
                
                // Основні ТМЦ	
                $table->text('tmc')->nullable()->comment('Основні ТМЦ');
                
                // Інв. ном. будівлі (споруди)	
                $table->string('inv_no')->nullable()->comment('Інв. ном. будівлі (споруди)');
                
                // Цех власник	
                $table->string('own_division')->nullable()->comment('Цех власник');
                
                // Уточнення по дефекту та роботах	
                $table->text('note_locale')->nullable()->comment('Уточнення по дефекту та роботах');
                
                // Примітка	
                $table->text('note')->nullable()->comment('Примітка');

                // Обґрунтування (п. Акту ЗВТО-25 / ID дефекту з ОСКАР)
                $table->text('grant')->nullable()->comment('Обґрунтування (п. Акту ЗВТО-25 / ID дефекту з ОСКАР)');

                $table->timestamps();

                // Створення індексів для оптимізації пошуку та звітів
                $table->index('division');
                $table->index('build');
                $table->index('inv_no');
                $table->index('year');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('construction_jobs');
    }
};
