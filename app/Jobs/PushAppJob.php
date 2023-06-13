<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;



class PushAppJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	#numero de intentos
	public $tries ;
	#intervalo de segundos entre intentos
	public $retryAfter ;

	protected $pushAppLib;

    /**
     * Create a new job instance.
     *
     * @return void
     */
	public function __construct($pushAppLib)
    {
		$this->tries = env('QUEUE_TRIES', 3);
		$this->retryAfter = env('QUEUE_RETRY_AFTER', 90);
		$this->pushAppLib = $pushAppLib;
	}

    /**
     * Execute the job.
     *
     * @return void
     */


	public function handle()
    {

		\Log::info("lanzando push desde job");
		$this->pushAppLib->send_push();
	}



}
