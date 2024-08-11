<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
           // AdminSeeder::class,

           UserSeeder::class,
            PersonalSeeder::class,
            PositionSeeder::class,
            ActSeeder::class,
            CategorySeeder::class,
            ClientSeeder::class,
            ControlSeeder::class,
            CriteriaSeeder::class,
            DimensionSeeder::class,
            DocSeeder::class,
            EquipmentSeeder::class,
            FactSeeder::class,
            FunSeeder::class,
            GoalSeeder::class,
            ImageSeeder::class,
            NomenclatureSeeder::class,
            ObjectiveSeeder::class,
            ProblemSeeder::class,
            ProjectSeeder::class,
            RoomSeeder::class,
            StageSeeder::class,
            StepSeeder::class,
           /*  */
        ]);
    }
}
