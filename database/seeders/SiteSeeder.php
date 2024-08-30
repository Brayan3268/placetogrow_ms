<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $site = new Site;

        $site->slug = 'Motos_la_45';
        $site->name = 'Yamaha la 45';
        $site->category_id = 1;
        $site->expiration_time = 20;
        $site->currency_type = 'COP';
        $site->site_type = 'OPEN';
        $site->save();

        $site = new Site;

        $site->slug = 'Ropa_la_45';
        $site->name = 'Euphoria';
        $site->category_id = 2;
        $site->expiration_time = 20;
        $site->currency_type = 'COP';
        $site->site_type = 'CLOSE';
        $site->save();

        $site = new Site;

        $site->slug = 'Computadores_la_45';
        $site->name = "Lenovo pc's";
        $site->category_id = 3;
        $site->expiration_time = 20;
        $site->currency_type = 'COP';
        $site->site_type = 'SUSCRIPTION';
        $site->save();
    }
}
