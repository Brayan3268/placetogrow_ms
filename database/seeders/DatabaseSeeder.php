<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            CategorySeeder::class,
            SiteSeeder::class,
            FieldspaysiteSeeder::class,
        ]);
    }
}
