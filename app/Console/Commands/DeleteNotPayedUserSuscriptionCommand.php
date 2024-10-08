<?php

namespace App\Console\Commands;

use App\Http\PersistantsLowLevel\UserSuscriptionPll;
use Illuminate\Console\Command;

class DeleteNotPayedUserSuscriptionCommand extends Command
{
    protected $signature = 'app:delete-not-payed-user-suscription-command';

    protected $description = 'Delete a user suscription when his pay is rejected enougth times';

    public function handle()
    {
        UserSuscriptionPll::delete_not_payed_user_suscription();

        $this->info('Comando ejecutado con éxito!');
    }
}
