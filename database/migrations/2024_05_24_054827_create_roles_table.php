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
        if (!Schema::hasTable('roles')){
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();            
            $table->timestamps();
        });
           // insert default roles
        DB::table('roles')->insert([
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Super Admin Role'],
            ['name' => 'Адмін', 'slug' => 'admin', 'description' => 'Роль админістратора'],
            ['name' => 'Модератор', 'slug' => 'moderator', 'description' => 'Роль модератора'],
            ['name' => 'Користувач', 'slug' => 'user', 'description' => 'Роль користувача'],
            // інженер якості
            ['name' => 'Інженер якості', 'slug' => 'quality-engineer', 'description' => 'Роль інженера якості'],
            // інженер з виробництва
            ['name' => 'Інженер виробництва', 'slug' => 'production-engineer', 'description' => 'Роль інженера виробництва'],
            // інженер з розробки
            ['name' => 'Інженер розробки', 'slug' => 'development-engineer', 'description' => 'Роль інженера розробки'],
            // начальник цеху
            ['name' => 'Начальник цеху', 'slug' => 'workshop-chief', 'description' => 'Роль начальника цеху'],
            ['name' => 'Начальник відділу', 'slug' => 'department-chief', 'description' => 'Роль начальника відділу'],
        ]);
    }
     
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained();
                $table->foreignId('user_id')->constrained();
                $table->timestamps();
            });
        }
        // add users in table column tn
        if (Schema::hasTable('users')
            && !Schema::hasColumn('users', 'tn')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('tn')->unique()->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('roles')) {
          Schema::dropIfExists('roles');
        }
        if (Schema::hasTable('role_user')) {
        Schema::dropIfExists('role_user');
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'tn')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('tn');
            });
        }
    }
};
