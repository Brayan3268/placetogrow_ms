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
        $suscription->save();
    }
}
