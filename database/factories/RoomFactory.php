<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Room;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    protected $model = Room::class;
    /**
     * Define the model's default state.
     *  protected $fillable = [
        'IDname',
        'name', 
        'description',
        'address'
    ];
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'IDname' => $faker->company,
            'name' => $faker->company,
            'description' => $faker->paragraph,
            'address' => $faker->address
        ];
    }
}
