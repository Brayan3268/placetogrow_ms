<?php

namespace App\Console\Commands;

use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use Illuminate\Console\Command;

class ReduceDaysCommand extends Command
{
    protected $signature = 'app:reduce-days-command';

    protected $description = 'Reduce 1 day in days_until_next_payment';

    public function handle()
    {
        UserSuscriptionPll::decrement_day_until_next_payment();

        $this->info('Comando ejecutado con éxito!');
    }
}
