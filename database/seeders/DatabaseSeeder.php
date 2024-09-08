<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            SiteSeeder::class,
            FieldspaysiteSeeder::class,
            SuscriptionSeeder::class,
        ]);
    }
}
