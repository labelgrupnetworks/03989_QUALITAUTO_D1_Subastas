<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UniversalJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    #numero de intentos
    public $tries = 5;
	#intervalo de segundos entre intentos
	public $retryAfter = 10;

	protected $rutaController;
	protected $function;
	protected $variable;
	/*


	*/
    /**
     * Create a new job instance.
     *
     * @return void
     */
	public function __construct($rutaController, $function, $variable)
    {
		$this->rutaController = $rutaController;
		$this->function = $function;
		#OJO me ha dado problemas al pasar un objeto proveniente de un modelo (Asigl0), he tenido que convertirlo a array para que no falle $lote->toarray()
		$this->variable = $variable;

	}


	public function middleware()
	{
		return [(new ThrottlesExceptions(5, 5))->backoff(5)];
	}

    /**
     * Execute the job.
     *
     * @return void
     */


	public function handle()
    {

		$controller = new $this->rutaController();

		$controller->{$this->function}($this->variable );

	}



}
