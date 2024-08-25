<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //register admin user 
        //INSERT INTO `users` (`id`, `name`, `email`, `tn`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`) VALUES (NULL, 'Дудкін Роман Євгенович', 'dudkinr@ukr.net', '2191', NULL, '$2y$12$b0zzHmQ.3jQ2WEJ.cycwUuq0DJcHjHIp/ik.YqkI5KRVoAgHmY9Rm', NULL, NULL, NULL, NULL, '2024-06-16 06:51:01', '2024-06-16 06:51:01');
        $user = new \App\Models\User();
        $user->name = 'Дудкін Роман Євгенович';
        $user->email = 'dudkin@khnpp.atom.gov.ua';
        $user->tn = '13344';
        $user->password = bcrypt('Qwerty123');
        $user->save();     
        // INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES (NULL, '', '', NULL, NULL);
        // 1,2,3,4,5...9
        $user->roles()->attach(1); // Super Admin
        $user->roles()->attach(2); // Admin
        $user->roles()->attach(3); // Manager
        $user->roles()->attach(4); // User
        $user->roles()->attach(5); // quality-engineer
        $user->roles()->attach(6); // production-engineer
        $user->roles()->attach(7); // development-engineer
        $user->roles()->attach(8); // workshop-chief
        $user->roles()->attach(9); // department-chief
    }
}
