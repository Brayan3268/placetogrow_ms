<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = new User();

        $user->name = 'Brayan';
        $user->last_name = 'Luján Muñoz';
        $user->email = 'brayan.lujan@evertecinc.com';
        $user->password = '12345678';
        $user->document_type = 'CC';
        $user->phone = '3111111111';
        $user->document = '1234567890';

        $user->save();

        $user = new User();

        $user->name = 'Gisela';
        $user->last_name = 'Muñoz Valencia';
        $user->email = 'gisela.munoz@evertecinc.com';
        $user->password = '12345678';
        $user->document_type = 'CC';
        $user->phone = '3222222222';
        $user->document = '1010101010';

        $user->save();

        $user = new User();

        $user->name = 'Miguel Ángel';
        $user->last_name = 'Luján Muñoz2';
        $user->email = 'miguel.lujan@evertecinc.com';
        $user->password = '12345678';
        $user->document_type = 'CC';
        $user->phone = '3333333333';
        $user->document = '0987654321';

        $user->save();
    }
}
