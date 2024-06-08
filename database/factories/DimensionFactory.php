<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dimension;
use Faker\Factory as FakerFactory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dimension>
 */
class DimensionFactory extends Factory
{
    protected $model = Dimension::class;
    /**
     * Define the model's default state.
     * protected $table = 'dimensions';
    
    protected $fillable = [
        'abv',
        'name',
        'kod',
        'description',
        'formula',
        'unit',
        'type',
        'value',
        'min_value',
        'max_value',
        'step',
        'default_value',
        'default_min_value',
        'default_max_value',
        'default_step',
        'default_type',
        'default_unit',
    ];
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'abv' => $faker->unique()->word,
            'name' => $faker->unique()->word,
            'kod' => $faker->unique()->word,
            'description' => $faker->sentence(10),
            'formula' => $faker->sentence(10),
            'unit' => $faker->word,
            'type' => $faker->word,
            'value' => $faker->randomFloat(2, 0, 100),
            'min_value' => $faker->randomFloat(2, 0, 100),
            'max_value' => $faker->randomFloat(2, 0, 100),
            'step' => $faker->randomFloat(2, 0, 100),
            'default_value' => $faker->randomFloat(2, 0, 100),
            'default_min_value' => $faker->randomFloat(2, 0, 100),
            'default_max_value' => $faker->randomFloat(2, 0, 100),
            'default_step' => $faker->randomFloat(2, 0, 100),
            'default_type' => $faker->word,
            'default_unit' => $faker->word,
            
        ];
    }
}
