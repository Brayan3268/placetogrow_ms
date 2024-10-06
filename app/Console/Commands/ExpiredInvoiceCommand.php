<?php

namespace App\Console\Commands;

use App\Http\PersistantsLowLevel\InvoicePll;
use Illuminate\Console\Command;

class ExpiredInvoiceCommand extends Command
{
    protected $signature = 'app:expired-invoice-command';

    protected $description = 'Change the status value when the invoices expired';

    public function handle()
    {
        InvoicePll::expired_invoices();

        $this->info('Comando ejecutado con éxito!');
    }
}
