<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $existingPermissions = DB::table('permissions')->pluck('name')->toArray();

        $newPermissions = [];

        foreach (Permissions::get_all_permissions() as $permission) {
            if (!in_array($permission, $existingPermissions)) {
                $newPermissions[] = ['name' => $permission, 'guard_name' => 'web'];
            }
        }

        if (!empty($newPermissions)) {
            DB::table('permissions')->insert($newPermissions);
        }
    }
}
