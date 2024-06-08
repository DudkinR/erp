<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Nomenclature;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Nomenclature>
 */
class NomenclatureFactory extends Factory
{
    protected $model = Nomenclature::class;
    /**
     * Define the model's default state.
     *
    protected $table = 'nomenclature';
    protected    $fillable = ['name', 'article', 'description', 'image'];
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'name' => $faker->company,
            'article' => $faker->unique()->word,
            'description' => $faker->paragraph,
            'image' => $faker->imageUrl(),
        ];
    }
}
