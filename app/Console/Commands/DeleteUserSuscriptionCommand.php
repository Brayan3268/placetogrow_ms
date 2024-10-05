<?php

namespace App\Console\Commands;

use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use Illuminate\Console\Command;

class DeleteUserSuscriptionCommand extends Command
{
    protected $signature = 'app:delete-user-suscription-command';

    protected $description = 'Delete a user suscription when expiration_time == 0';

    public function handle()
    {
        UserSuscriptionPll::delete_user_suscription_expiration_time();

        $this->info('Comando ejecutado con éxito!');
    }
}
