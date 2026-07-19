<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Experience;
use App\Models\System;
use App\Models\Type;
use App\Models\Risk;
class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // створимо 20 досвідів
        Experience::factory(20)->create()->each(function($experience) {
            // випадкові системи
            $systems = System::inRandomOrder()->take(rand(1,3))->pluck('id');
            $experience->systems()->sync($systems);

            // випадкове обладнання
            $equipments = Type::where('slug','equipment')->first()->children()->inRandomOrder()->take(rand(1,3))->pluck('id');
            $experience->equipments()->sync($equipments);

            // випадкові дії
            $actions = Type::where('slug','action')->first()->children()->inRandomOrder()->take(rand(1,2))->pluck('id');
            $experience->actions()->sync($actions);

            // випадкові причини
            $causes = Type::where('slug','cause')->first()->children()->inRandomOrder()->take(rand(1,2))->pluck('id');
            $experience->reasons()->sync($causes);

            // випадкові ризики
            $risks = Risk::inRandomOrder()->take(rand(1,3))->pluck('id');
            $experience->risks()->sync($risks);
        });
    }
}
