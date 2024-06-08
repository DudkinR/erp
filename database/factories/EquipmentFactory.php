<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Equipment;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;
    /**
     * Define the model's default state.
     *  protected $table = 'equipments';
    // column name
    protected $fillable = [
        'IDname',
        'name', 
        'description',
        'manufacture_date',
        'expiration_date',
        'verification_date',
        'last_verification_date',
        'next_verification_date',
        'address'
    ];
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'IDname' => $faker->unique()->word,
            'name' => $faker->company,
            'description' => $faker->paragraph,
            'manufacture_date' => $faker->date(),
            'expiration_date' => $faker->date(),
            'verification_date' => $faker->date(),
            'last_verification_date' => $faker->date(),
            'next_verification_date' => $faker->date(),
            'address' => $faker->address,
        ];
    }
}
