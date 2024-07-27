<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dates = [
            ['date_create' => '2024-07-26', 'date_expiration' => '2025-07-26'],
            ['date_create' => '2024-07-27', 'date_expiration' => '2025-07-27'],
            // Agrega más datos según sea necesario
        ];
    }
}
