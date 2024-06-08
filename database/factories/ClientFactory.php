<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;
    /**
     * Define the model's default state.
     *   protected $fillable = ['name', 'business_region', 'registration_date', 'code'];
    


     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'name' => $faker->unique()->company,
            'business_region' => $faker->city,
            'registration_date' => $faker->date(),
            'code' => $faker->unique()->randomNumber(8),
        ];
    }
}
