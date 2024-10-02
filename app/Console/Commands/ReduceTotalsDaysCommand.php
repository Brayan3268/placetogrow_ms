<?php

namespace App\Console\Commands;

use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use Illuminate\Console\Command;

class ReduceTotalsDaysCommand extends Command
{
    protected $signature = 'app:reduce-expiration-time-command';

    protected $description = 'Reduce 1 day in total days user suscription plan';

    public function handle()
    {
        UserSuscriptionPll::decrement_expiration_time();

        $this->info('Comando ejecutado con éxito!');
    }
}
