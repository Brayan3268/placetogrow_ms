<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = new User();

        $user->name = 'Brayan Luján Muñoz';
        $user->email = 'brayan.lujan@evertecinc.com';
        $user->password = '12345678';
        $user->document_type = 'CC';
        $user->document = '1234567890';

        $user->save();

        $user = new User();

        $user->name = 'Gisela Muñoz Valencia';
        $user->email = 'gisela.munoz@evertecinc.com';
        $user->password = '12345678';
        $user->document_type = 'CC';
        $user->document = '1010101010';

        $user->save();

        $user = new User();

        $user->name = 'Miguel Ángel Luján Muñoz';
        $user->email = 'miguel.lujan@evertecinc.com';
        $user->password = '12345678';
        $user->document_type = 'CC';
        $user->document = '0987654321';

        $user->save();
    }
}
