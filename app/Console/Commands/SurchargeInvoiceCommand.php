<?php

namespace App\Console\Commands;

use App\Http\PersistantsLowLevel\InvoicePll;
use Illuminate\Console\Command;

class SurchargeInvoiceCommand extends Command
{
    protected $signature = 'app:surcharge-invoice-command';

    protected $description = 'Add the surcharge value in the date_surcharge';

    public function handle()
    {
        InvoicePll::add_surcharge();

        $this->info('Comando ejecutado con éxito!');
    }
}
