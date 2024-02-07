<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@app.com',
            'password' => bcrypt('123456')
        ])->assignRole('admin');
        $this->call(CategorySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(EventSeeder::class);
    }
}
