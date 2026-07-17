<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        //  UserSeeder::class,
        //  LocationSeeder::class,
        //  VehicleSeeder::class,
        ]);
    }
}
