<?php

namespace App\Console\Commands\User;

use App\Services\User\UserRegisterService;
use Illuminate\Console\Command;

class NotifyRejectedKycUsers extends Command
{
    protected $signature = 'label:notify-rejected-kyc-users';
    protected $description = 'Notifica a los usuarios con KYC rechazado';

    public function handle()
    {
		$this->info("Iniciando notificación de KYCs rechazados...");
        (new UserRegisterService)->notifyKycPendingUsers();
        $this->info("Notificación completada.");
        return Command::SUCCESS;
    }
}
