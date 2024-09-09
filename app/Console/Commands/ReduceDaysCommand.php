<?php

namespace App\Console\Commands;

use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use Illuminate\Console\Command;

class ReduceDaysCommand extends Command
{
    protected $signature = 'app:reduce-days-command';

    protected $description = 'Reduce 1 day at day in the users suscription plans';

    public function handle()
    {
        UserSuscriptionPll::decrement_day();

        $this->info('Comando ejecutado con éxito!');
    }
}
