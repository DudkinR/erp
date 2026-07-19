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
            'text_ru' => $this->faker->sentence(),
            'text_uk' => $text ,
            'text_en' => $this->faker->sentence(),
            'npp' => $this->faker->randomElement(['0','1','2']),
            'year' => $this->faker->year(),
            'consequence' => $this->faker->numberBetween(1,10),
            'accepted' => $this->faker->boolean(),
            'author_tn' => $this->faker->randomNumber(5),
        ];
}

}
