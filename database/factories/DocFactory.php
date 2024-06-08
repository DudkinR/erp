<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Doc;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doc>
 */
class DocFactory extends Factory
{
    protected $model = Doc::class;
    /**
     * Define the model's default state.
     *protected $table = 'documentations';    
    // fillable fields
    protected $fillable = ['name', 'path', 'slug', 'lng', 'link', 'description', 'revision_date', 'publication_date', 'creation_date', 'deletion_date', 'last_change_date', 'last_view_date', 'category_id', 'status'];
    // relationship doc - doc

     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [ 
            'name' => $faker->company,
            'path' => $faker->url,
            'slug' => $faker->slug,
            'lng' => $faker->languageCode,
            'link' => $faker->url,
            'description' => $faker->paragraph,
            'revision_date' => $faker->date(),
            'publication_date' => $faker->date(),
            'creation_date' => $faker->date(),
            'deletion_date' => $faker->date(),
            'last_change_date' => $faker->date(),
            'last_view_date' => $faker->date(),
            'category_id' => $faker->numberBetween(1, 10),
        ];
    }
}
