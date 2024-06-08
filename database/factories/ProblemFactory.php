<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Problem;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Problem>
 */
class ProblemFactory extends Factory
{
    protected $model = Problem::class;
    /**
     * Define the model's default state.
     *  protected $table = 'problems';
    // use  fillable
     'project_id',
    'stage_id',
    'step_id',
    'task_id',
    'user_id',
    'responsible_position_id',
    'control_id',
    'name',
    'description',
    'priority',
    'date_start',
    'date_end',
    'deadline',
    'status'
    ];
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'project_id' => $faker->randomDigitNotNull,
            'stage_id' => $faker->randomDigitNotNull,
            'step_id' => $faker->randomDigitNotNull,
            'task_id' => $faker->randomDigitNotNull,
            'user_id' => $faker->randomDigitNotNull,
            'responsible_position_id' => $faker->randomDigitNotNull,
            'control_id' => $faker->randomDigitNotNull,
            'name' => $faker->company,
            'description' => $faker->paragraph,
            'priority' => $faker->randomDigitNotNull,
            'date_start' => $faker->date(),
            'date_end' => $faker->date(),
            'deadline' => $faker->date(),
            'status' => $faker->randomDigitNotNull,          
        ];
    }
}
