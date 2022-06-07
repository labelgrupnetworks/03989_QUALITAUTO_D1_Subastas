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
	public $tries = 5;
	#intervalo de segundos entre intentos
	public $retryAfter = 60;

	protected $emailLib;

    /**
     * Create a new job instance.
     *
     * @return void
     */
	public function __construct($emailLib)
    {
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
