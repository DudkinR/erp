<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Fact;
use Faker\Factory as FakerFactory; 

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fact>
 */
class FactFactory extends Factory
{
    protected $model = Fact::class;
    /**
     * Define the model's default state.
     *
     *    use HasFactory;
    // table name
    protected $table = 'facts';
    // fillable fields
    protected $fillable = [
        'name',
        'description',
        'image',
        'status' 'active','freeze','inactive','completed','closed'
    ];
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'name' => $faker->company,
            'description' => $faker->paragraph,
            'image' => $faker->imageUrl(),
            'status' => $faker-> randomElement(['active','freeze','inactive','completed','closed']),
        ];
    }
}
