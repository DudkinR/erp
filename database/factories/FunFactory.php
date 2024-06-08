<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Fun;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fun>
 */
class FunFactory extends Factory
{
    /**
     * Define the model's default state.
     *
    protected $table = 'functs';
    // fillable
    protected $fillable = ['name','description'];
    // relationships goal_id to funct_id
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'name' => $faker->company,
            'description' => $faker->paragraph,
        ];
    }
}
