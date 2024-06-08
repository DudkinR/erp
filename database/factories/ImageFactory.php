<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Image;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    protected $model = Image::class;
    /**
     * Define the model's default state.
     *
    use HasFactory;
    // table name
    protected $table = 'images';
    
    protected $fillable = ['name', 'path', 'extension', 'size', 'mime_type', 'url', 'alt', 'title', 'description'];

    // nomenclatures
    public function nomenclatures()
    {
        return $this->belongsToMany(Nomenclature::class, 'image_nomenclature', 'image_id', 'nomenclature_id');
    }
     * 
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create('uk_UA');
        return [
            'name' => $faker->unique()->word,
            'path' => $faker->imageUrl(),
            'extension' => $faker->fileExtension,
            'size' => $faker->numberBetween(100, 9000),
            'mime_type' => $faker->mimeType,
            'url' => $faker->url,
            'alt' => $faker->word,
            'title' => $faker->word,
            'description' => $faker->sentence(10),
        ];
    }
}
