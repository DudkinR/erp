<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Nomenclature;
use Faker\Factory as FakerFactory;

/**
 * 
 */
class NomenclatureFactory extends Factory
{
    protected $model = Nomenclature::class;
    /**
     * Define the model's default state.
     *
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'name' => $faker->company,
            'article' => $faker->unique()->word,
            'description' => $faker->paragraph,
          
        ];
    }
}
