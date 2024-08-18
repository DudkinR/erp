<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use  App\Models\Building;
use App\Models\Phone;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Room::factory()->count(10)->create();
        $rooms = Room::all();
        $phones = Phone::all();
        $buildings = Building::all();
        for($i = 0; $i < 10; $i++){
            foreach ($rooms as $room) {
                $room->phones()->attach($phones->random()->id);
                $room->buildings()->attach($buildings->random()->id);
            }
        }
        


    }
}
