<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Goal;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goal>
 */
class GoalFactory extends Factory
{
    protected $model = Goal::class;
    /**
     * Define the model's default state.
     *
    protected $table = 'goals';
    // primary key
    protected $primaryKey = 'id';
    // fillable fields
    protected $fillable = ['name', 'description', 'due_date', 'completed', 'completed_date', 'status'];

     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'name' => $faker->company,
            'description' => $faker->paragraph,
            'due_date' => $faker->date(),
            'completed' => $faker->boolean,
            'completed_date' => $faker->date(),
            'status' => $faker->randomElement(['виконано', 'в процесі', 'не виконано']),
        ];
    }
}
