<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Objective;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Objective>
 */
class ObjectiveFactory extends Factory
{
    protected $model = Objective::class;
    /**
     * Define the model's default state.
     *    protected $table = 'objectives';
    // columns
    protected $fillable = ['name', 'description'];
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
