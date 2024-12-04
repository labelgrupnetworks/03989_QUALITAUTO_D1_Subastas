<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $notificationEmail;
	public $toEmail;

    /**
     * Create a new job instance.
     *
     * @param array $users Lista de usuarios
     */
    public function __construct(Mailable $notificationEmail, $toEmail)
    {
        $this->notificationEmail = $notificationEmail;
		$this->toEmail = $toEmail;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
		Mail::to($this->toEmail)->send($this->notificationEmail);
    }
}
