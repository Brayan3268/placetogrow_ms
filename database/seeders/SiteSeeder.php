<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $site = new Site();

        $site->slug = 'Motos_la_45';
        $site->name = 'Yamaha la 45';
        $site->document_type = 'CC';
        $site->document = '1234567890';
        $site->category_id = 1;
        $site->expiration_time = 20;
        $site->current_type = 'COP';
        $site->site_type = 'OPEN';
        $site->return_url = 'http://127.0.0.1:8000/sites/1';
        $site->save();

        $site = new Site();

        $site->slug = 'Ropa_la_45';
        $site->name = 'Euphoria';
        $site->document_type = 'CC';
        $site->document = '0987654321';
        $site->category_id = 2;
        $site->expiration_time = 20;
        $site->current_type = 'COP';
        $site->site_type = 'CLOSE';
        $site->return_url = 'http://127.0.0.1:8000/sites/2';
        $site->save();

        $site = new Site();

        $site->slug = 'Computadores_la_45';
        $site->name = "Lenovo pc's";
        $site->document_type = 'CC';
        $site->document = '7361092764';
        $site->category_id = 3;
        $site->expiration_time = 20;
        $site->current_type = 'COP';
        $site->site_type = 'SUSCRIPTION';
        $site->return_url = 'http://127.0.0.1:8000/sites/3';
        $site->save();
    }
}
