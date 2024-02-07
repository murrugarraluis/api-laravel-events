<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        City::factory()->create(['name'=>'Lima']);
        City::factory()->create(['name'=>'Chiclayo']);
        City::factory()->create(['name'=>'Trujillo']);
        City::factory()->create(['name'=>'Arequipa']);
    }
}
