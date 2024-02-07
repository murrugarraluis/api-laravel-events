<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name'=>'create categories']);
        Permission::create(['name'=>'update categories']);
        Permission::create(['name'=>'destroy categories']);

        Permission::create(['name'=>'create events']);
        Permission::create(['name'=>'update events']);
        Permission::create(['name'=>'destroy events']);

        Permission::create(['name'=>'create cities']);
        Permission::create(['name'=>'update cities']);
        Permission::create(['name'=>'destroy cities']);
    }
}
