<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Personal;
use App\Models\User;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Personal>
 */
class PersonalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $faker = FakerFactory::create('uk_UA');
        $users = User::whereNull('tn')->get(); // Отримуємо всіх користувачів з tn == null

        if ($users->isEmpty()) {
            // Якщо немає користувачів з tn == null, повертаємо стандартний набір даних
            return [
                'tn' => $faker->unique()->randomNumber(),
                'nickname' => $faker->userName,
                'fio' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'date_start' => $faker->date(),
            ];
        }

        // Вибираємо випадкового користувача з tn == null
        $user = $users->random();

        return [
            'tn' => $user->tn,
            'nickname' => $faker->userName,
            'fio' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->phoneNumber,
            'date_start' => $faker->date(),
        ];
    }
        
    
}