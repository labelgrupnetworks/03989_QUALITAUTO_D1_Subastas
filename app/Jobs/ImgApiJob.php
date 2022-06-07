<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Http\Controllers\apilabel\ImgController;
use phpDocumentor\Reflection\Types\Boolean;

class ImgApiJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	#numero de intentos
	public $tries = 5;
	#intervalo de segundos entre intentos
	public $retryAfter = 10;

	protected $images = array();
	#si vienen el identificador del lote
	protected $deleteAll = false;

    /**
     * Create a new job instance.
     *
     * @return void
     */
	public function __construct($images, $deleteAll= false)
    {
		$this->images = $images;
		$this->deleteAll = $deleteAll;
	}

    /**
     * Execute the job.
     *
     * @return void
     */


	public function handle()
    {
		$imgController = new ImgController();


		#primero se borran si así se debe hacer, necesitamos que venga algua imagen ya que el origen sol oesta en el array de imágenes
		#se puede dar el caso de que no se borren las imagenes is no bviene una nueva...
		if(!empty($this->deleteAll) && count($this->images) > 0){

			$parameters=array("idoriginlot" => $this->images[0]["idoriginlot"]);
			$imgController->eraseAllImg($parameters);
		}

		#creamos las imagenes actuales, si hay
		if(count($this->images) > 0){
			$imgController->createImg($this->images);
		}


	}



}
