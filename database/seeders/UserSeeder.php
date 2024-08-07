<?php

namespace Database\Seeders;

use App\Constants\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->upsert($this->add_users(), 'email');

        User::query()->where('email', 'brayan.lujan@evertecinc.com')->first()->assignRole(Roles::SUPER_ADMIN);
    }

    public function add_users()
    {
        return [
            [
                'name' => 'Brayan',
                'last_name' => 'Luján Muñoz',
                'email' => 'brayan.lujan@evertecinc.com',
                'password' => bcrypt('12345678'),
                'document_type' => 'CC',
                'phone' => '3111111111',
                'document' => '1234567890',
            ],
        ];
    }
}
