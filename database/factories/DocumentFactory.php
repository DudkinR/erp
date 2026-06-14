<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition()
    {
        return [
            'inv_no' => strtoupper(Str::random(8)), // унікальний інв. номер
            'doc_type' => $this->faker->randomElement(['STD', 'POLICY', 'GUIDE']),
            'code' => strtoupper(Str::random(5)),
            'organization' => $this->faker->company(),
            'short_content' => $this->faker->sentence(6),
            'date_reg' => $this->faker->date(),
            'date_start' => $this->faker->date(),
            'date_end' => $this->faker->optional()->date(),
            'distribution' => $this->faker->word(),
            'replaced_content' => $this->faker->optional()->sentence(),
            'replaced_by' => $this->faker->optional()->word(),
            'change_no' => $this->faker->numberBetween(0, 10),
            'page_count' => $this->faker->numberBetween(1, 200),
            'note' => $this->faker->optional()->sentence(),
            'storage_location' => $this->faker->city(),
            'registration_date' => $this->faker->date(),
            'is_cancelled' => $this->faker->boolean(),
            'cancellation_date' => null,
            'is_reissued' => $this->faker->boolean(),
            'author' => $this->faker->name(),
            'approved_by' => $this->faker->name(),
            'project' => $this->faker->word(),
        ];
    }
}
