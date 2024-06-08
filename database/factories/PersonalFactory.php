<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Personal;
use App\Models\User;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Personal>
 */
class PersonalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        $users = User::all();
        foreach ($users as $user) {
         $personal = Personal::where('tn', $user->tn)->first();
            if($user->tn == null)
            {
                continue;
            }

            if ($personal) {
                continue;
            }
            else
            {
                return [
                    'tn' => $user->tn,
                    'nickname' => $this->faker->userName,
                    'fio' => $this->faker->name,
                    'email' => $this->faker->unique()->safeEmail,
                    'phone' => $this->faker->phoneNumber,
                    'date_start' => $this->faker->date(),
                ];
            }
        }
    }
}
