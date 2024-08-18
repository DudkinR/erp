<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Building>
 */
class BuildingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {// `id`, `name`, `address`, `city`, `state`, `zip`, `country`, `abv`, `slug`, `organization`, `status`, `image`, `created_at`, `updated_at`;
        return [
            'name' => $this->faker->company,
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'state' => $this->faker->country,
            'zip' => $this->faker->postcode,
            'country' => $this->faker->country,
            'abv' => $this->faker->countryCode,
            'slug' => $this->faker->slug,
            'organization' => $this->faker->company,
            'status' => $this->faker->randomElement([1, 2, 3]),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
