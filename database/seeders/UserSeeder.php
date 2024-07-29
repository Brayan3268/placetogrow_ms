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
        User::query()->where('email', 'gisela.munoz@evertecinc.com')->first()->assignRole(Roles::ADMIN);
        User::query()->where('email', 'miguel.lujan@evertecinc.com')->first()->assignRole(Roles::GUEST);
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
            [
                'name' => 'Gisela',
                'last_name' => 'Muñoz Valencia',
                'email' => 'gisela.munoz@evertecinc.com',
                'password' => bcrypt('12345678'),
                'document_type' => 'CC',
                'phone' => '3222222222',
                'document' => '1010101010',
            ],
            [
                'name' => 'Miguel Ángel',
                'last_name' => 'Luján Muñoz',
                'email' => 'miguel.lujan@evertecinc.com',
                'password' => bcrypt('12345678'),
                'document_type' => 'CC',
                'phone' => '3333333333',
                'document' => '0987654321',
            ],
        ];
    }
}
