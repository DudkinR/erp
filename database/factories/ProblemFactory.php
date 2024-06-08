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
    /**
     * Define the model's default state.
     *  protected $table = 'problems';
    // use  fillable
    protected $fillable = [
        'name',
        'description', 
        'priority', 
        'date_start', 
        'date_end', 
        'deadline', 
        'status', 
        'project_id', 
        'stage_id', 
        'step_id', 
        'task_id',
        'user_id',
        'responsible_position_id',
        'control_id'
    ];
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
