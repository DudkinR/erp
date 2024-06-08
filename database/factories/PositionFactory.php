<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Position;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    protected $model = Position::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        //    protected $fillable = ['name', 'description', 'start', 'data_start', 'closed', 'data_closed'];
        $name = $faker->unique()->jobTitle;
        $description = $faker->sentence(10);
        $start = $faker-> date();
        $data_start = $faker->date();
        $closed = $faker->optional()->date();

        return [
            'name' => $name,
            'description' => $description,
            'start' => $start,
            'data_start' => $data_start,
            'closed' => $closed,
            'data_closed' => $closed,
        ];
    }
}
