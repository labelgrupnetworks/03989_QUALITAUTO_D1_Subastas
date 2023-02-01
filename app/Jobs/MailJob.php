<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;



class MailJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	#numero de intentos
	public $tries ;
	#intervalo de segundos entre intentos
	public $retryAfter ;

	protected $emailLib;

    /**
     * Create a new job instance.
     *
     * @return void
     */
	public function __construct($emailLib)
    {
		$this->tries = env('QUEUE_TRIES', 3);
		$this->retryAfter = env('QUEUE_RETRY_AFTER', 90);
		$this->emailLib = $emailLib;
	}

    /**
     * Execute the job.
     *
     * @return void
     */


	public function handle()
    {

		$this->emailLib->send_email_queue();
	}



}
