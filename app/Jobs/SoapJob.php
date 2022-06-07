<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use SimpleXMLElement;

class SoapJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	#numero de intentos
	public $tries = 5;
	#intervalo de segundos entre intentos
	public $retryAfter = 10;

	protected $xml;
	protected $function;
	protected $rutaController;
	protected $responseFunc;

    /**
     * Create a new job instance.
     *
     * @return void
     */

	public function __construct($xml, $function, $rutaController, $responseFunc = NULL)
    {
		#xml en formato texto
		$this->xml = $xml;
		#funcion soap a la que llamar
		$this->function = $function;
		#ruta del controlador que ejecutará la funcion
		$this->rutaController = $rutaController;

		$this->responseFunc =  $responseFunc;
    }
    /**
     * Execute the job.
     *
     * @return void
     */


	public function handle()
    {

		$controller = new $this->rutaController();
		$res = $controller->QueueCall($this->xml, $this->function);
		if(!empty($this->responseFunc)){
			$controller->{$this->responseFunc}($res);
		}
	}



}
