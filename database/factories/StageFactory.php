<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Stage;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stage>
 */
class StageFactory extends Factory
{
    protected $model = Stage::class;
    /**
     * Define the model's default state.
     * protected $fillable = [
        'name',
        'description',
    ];
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
