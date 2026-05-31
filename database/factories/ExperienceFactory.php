<?php

namespace Database\Factories;
use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition()
{
    $keywords = [
        ['транспорт','автобус','службовий'],
        ['громадський','маршрутка','поїзд'],
        ['слизька','поверхня','ожеледиця'],
        ['освітлення','темрява'],
        ['погода','дощ','сніг'],
        ['документи','папери'],
        ['комп\'ютер','ПЕОМ','монітор'],
        ['відрядження','службовий транспорт'],
        ['відрядження','громадський транспорт'],
        ['обстріл','агресія','війна'],
        ['гарячий','опік','температура'],
        ['струм','електрика'],
        ['мережа','коротке замикання'],
        ['кондиціонер','електрика'],
        ['переохолодження','застуда'],
        ['вентиляція','повітря'],
        ['фреон','витік'],
        ['радіація','аварія'],
        ['пожежа','займання'],
        ['вибух','детонація'],
        ['шлях','евакуація','перешкода'],
        ['обстріл','ракета','агресія'],
    ];

    $set = $this->faker->randomElement($keywords);
    $text = $this->faker->sentence(6) . ' ' . implode(' ', $set);

    return [
        'text_uk'     => $text,
        'text_ru'     => $text,
        'text_en'     => $this->faker->sentence(6),
        'npp'         => $this->faker->numberBetween(1, 100),
        'year' => $this->faker->numberBetween(2025, 2026),
        'consequence' => $this->faker->numberBetween(1, 5),
        'accepted'    => $this->faker->boolean(),
        'author_tn'   => $this->faker->numberBetween(1, 51000),
    ];
}

}
