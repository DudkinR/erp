<?php

namespace Database\Factories;


use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'name' => $faker->company,
            'description' => $faker->paragraph,
            'priority' => $faker->numberBetween(1, 5),
            'number' => $faker->unique()->randomNumber(8),
            'date' => $faker->date(),
            'amount' => $faker->randomFloat(2, 1000, 100000),
            'client' => $faker->company,
            'current_state' => $faker->randomElement(['ініційований', 'в процесі', 'завершено']),
            'execution_period' => $faker->numberBetween(1, 24), // in months
            'payment_percentage' => $faker->numberBetween(0, 100),
            'shipping_percentage' => $faker->numberBetween(0, 100),
            'debt_percentage' => $faker->numberBetween(0, 100),
            'currency' => $faker->currencyCode,
            'operation' => $faker->word,
        ];
    }
}
