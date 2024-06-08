<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Control; 
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Control>
 */
class ControlFactory extends Factory
{
    protected $model = Control::class;
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
            'complite_date' => $faker->date(),
            'dedline_date' => $faker->date(),
            'status' => $faker->randomElement(['виконано', 'в процесі', 'не виконано']),
        ];
    }
}
