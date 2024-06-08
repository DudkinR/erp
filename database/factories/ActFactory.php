<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Act;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Act>
 */
class ActFactory extends Factory
{
    protected $model = Act::class;
    // 
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *    protected $table = 'acts';
    // fillable fields
    protected $fillable = [
        'name',
        'description',
        'complite_date',
        'dedline_date',
        'status'
    ];
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
