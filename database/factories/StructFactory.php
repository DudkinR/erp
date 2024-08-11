<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Struct;
use App\Helpers\StringHelpers;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StructFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    { 
       return $this->parentORnull();
        
    }

    public function parentORnull(): array 
    {
        // выдавать случайный элемент из всех структур(если есть хоть один элемент) или null(10% если нет  элементов)
        //сгенерировать массив ['abv','name', 'description','parent_id','kod','status'];
        $all_structs = Struct::orderBy('id', 'desc')->get();
        if ($all_structs->count() > 0) {
            $rand = rand(0, $all_structs->count() - 1);
            return $this->gen_mass($all_structs[$rand]->id);
        } else {
            if (rand(0, 100) < 10) {
                return  $this->gen_mass(0);
            } else {
                return $this->parentORnull();
            }
        
        } 

    }

    public function gen_mass($parent_id): array
    {
        $status = [
            'active',
            'inactive', 
            'deleted',
            'draft',
            'published',
        ];
        $mass = [];
        // название должности - професиональное название
        $mass['name'] = $this->faker->jobTitle();
        $mass['abv'] = StringHelpers::abv($mass['name']);
        $mass['description'] = $this->faker->text();
        $mass['parent_id'] = $parent_id;
        $mass['kod'] = $this->faker->word();
        if ($parent_id == 0) {
            $mass['status'] = $status[0];
        } else
        $mass['status'] = $status[rand(0, 4)];
        return $mass;
    }
}
