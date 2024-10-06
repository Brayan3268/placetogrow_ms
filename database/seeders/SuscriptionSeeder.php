<?php

namespace Database\Seeders;

use App\Constants\CurrencyTypes;
use App\Constants\FrecuencyCollection;
use App\Models\Suscription;
use Illuminate\Database\Seeder;

class SuscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $suscription = new Suscription;

        $suscription->name = 'Netflix 6 meses';
        $suscription->description = 'A.A.A.A.A.A.A.A';
        $suscription->amount = 20000;
        $suscription->currency_type = CurrencyTypes::COP;
        $suscription->expiration_time = 180;
        $suscription->frecuency_collection = FrecuencyCollection::WEEK;
        $suscription->site_id = 3;
        $suscription->number_trys = 3;
        $suscription->how_often_days = 2;
        $suscription->save();

        $suscription = new Suscription;

        $suscription->name = 'Netflix 12 meses';
        $suscription->description = 'B.B.B.B.B.B.B';
        $suscription->amount = 80000;
        $suscription->currency_type = CurrencyTypes::COP;
        $suscription->expiration_time = 360;
        $suscription->frecuency_collection = FrecuencyCollection::MONTH;
        $suscription->site_id = 3;
        $suscription->number_trys = 3;
        $suscription->how_often_days = 2;
        $suscription->save();

        $suscription = new Suscription;

        $suscription->name = 'Netflix 2 semanas';
        $suscription->description = 'C.C.C.C';
        $suscription->amount = 60000;
        $suscription->currency_type = CurrencyTypes::COP;
        $suscription->expiration_time = 15;
        $suscription->frecuency_collection = FrecuencyCollection::WEEK;
        $suscription->site_id = 3;
        $suscription->number_trys = 3;
        $suscription->how_often_days = 2;
        $suscription->save();

        $suscription = new Suscription;

        $suscription->name = 'Netflix 1 semana';
        $suscription->description = 'D.D';
        $suscription->amount = 20000;
        $suscription->currency_type = CurrencyTypes::COP;
        $suscription->expiration_time = 7;
        $suscription->frecuency_collection = FrecuencyCollection::WEEK;
        $suscription->site_id = 3;
        $suscription->number_trys = 3;
        $suscription->how_often_days = 2;
        $suscription->save();
    }
}
