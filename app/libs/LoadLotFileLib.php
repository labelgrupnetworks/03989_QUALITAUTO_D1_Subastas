<?php

namespace App\libs;
use App\Models\V5\FgAsigl0;
use App\Models\V5\FgOrtsec0;
use App\Models\V5\FgOrtsec1;
use App\Models\V5\FgSub;
use App\Models\V5\FxSec;
use App\Models\V5\FgCaracteristicas;
use App\Models\V5\FgOrlic;
use App\Models\Subasta;
use App\Http\Controllers\apilabel\LotController;
use App\Http\Controllers\apilabel\ImgController;
use App\Http\Controllers\admin\subasta\SubastaController;
use Config;
use App\Jobs\ImgApiJob;
use App\Jobs\UniversalJob;
class LoadLotFileLib {

	const VENTA = "MOTORV";
	const ONLINE = "MOTORO";

	public function __construct($codCli){

		/*
			PUNTUALIZACIONES:
			Van a existir solo 2 subastas una de venta directa y otra online
			las carga se realiza por cada cliente que es cedente, por lo que debemos saber que vehículos ya existen de ese concesionario

		*/
		$subasta = new Subasta();
		$subasta->cod =  self::ONLINE;
		$this->scaleRanges = $subasta->allScales();


		$this->idAuctions = array( self::VENTA , self::ONLINE );
		#$this->datesFgSub = FgSub::select('dfec_sub', 'dhora_sub', 'hfec_sub', 'hhora_sub', 'tipo_sub')->where('cod_sub', $idAuction)->first();

		#cargamos las caracteristicas y las caracteriticas value
		$this->subastaController = new SubastaController();
		$this->subastaController->fgCaracteristicas = FgCaracteristicas::select('name_caracteristicas', 'id_caracteristicas', 'filtro_caracteristicas','value_caracteristicas')->get();
		$this->subastaController->featureValues = $this->subastaController->existingFeatureValues();
		//$this->ref =FgAsigl0::select(" nvl(max(ref_asigl0),0) as ref")->wherein("sub_asigl0", [self::ONLINE,self::VENTA])->first()->ref;


		$this->idOrigins=$this->existingLotsCedente($codCli);


		$this->categories = self::existingCategories();
		$this->subCategories = self::existingSubCategories();
		$this->codCli = $codCli;

	}

	public function loadMotorFlash($xml){

		$featuresList = array("estado" => "estado","matricula" => "matrícula","chasis" => "Bastidor","precio" => "precio origen", "tipoVehiculo" => "tipo vehículo" ,"precio_nuevo" =>"precio nuevo","precio_financiado" =>"precio financiado", "cuota" => "cuota",	 "marchas" => "marchas",
							 "potencia" => "potencia", "plazas" => "plazas", "fecha_matriculacion" => "fecha de matriculación",
							 "ano_matriculacion" => "Año de matriculación",  "consumo_carretera" => "consumo carretera",
							 "emisiones" => "emisiones","consumo_mixto" => "consumo mixto",  "garantia" => "garantía",
							 "version" => "versión", "kilometros"=>"km", "hp" => "hp", "cilindrada" => "cilindrada",
							 "consumo_urbano" => "consumo urbano",   "fecha_fabrica" => "fecha fabricación", "combustible" => "combustible",
							 "cambio" => "cambio", "color" => "color", "metalizado" => "metalizado", "carroceria" => "carrocería",
							 "localidad" =>"ubicación", "modelo" =>"modelo","puertas" => "puertas",
							 "traccion" => "tracción", "distintivo" => "distintivo", "peso_vacio" => "peso vacio", "alto" => "alto",
							  "ancho" => "ancho", "largo" => "largo", "batalla" => "batalla", "pesoMax" =>"peso máximo",
							  "aceleracion" => "aceleracion", "velocidadMax" => "velocidad máxima", "provincia" => "provincia",
							  "poblacion" => "población", "evaluacion_precio " => "evaluación precio", "estandarEmisiones" => "estándar emisiones",
							  "cilindros" => "cilindros", "par" => "par", "neumaticosD" => "neumáticos delanteros",  "neumaticosT" => "neumáticos traseros",
							  "maletero" => "Maletero",  "deposito" => "depósito", "ahorro" => "ahorro",
							  "precioFinanciadoRangoMax" => "Precio financiado max", "precioFinanciadoRangoMin" => "Precio financiado min", "cuotaRangoMax" => "Cuota max", "cuotaRangoMin" => "Cuota min", "precioMaxMercado" => "Precio max mercado",  "precioMinMercado" => "Precio min mercado"
							);

		#$addTime = 0;
		$newLots = [];
		$newLots[self::VENTA]=[];
		$newLots[self::ONLINE]=[];
		$updateLots = [];
		$updateLots[self::VENTA]=[];
		$updateLots[self::ONLINE]=[];


		$imagenes = [];


		foreach($xml as $anuncio){

			#inicializamos el $lote a crear
			$lot=array();
					#pasamos los valores del xml a un array
					$vehiculo = array();

					//$vehiculo["localidad"] = trim($anuncio->localidad->__toString());
					$vehiculo["extras"] ="";
					foreach($anuncio as $key => $value){

						if($key == "fotos"){
							$vehiculo["imagenes"] = array();
							foreach( $value as $imagen){
								$vehiculo["imagenes"][] = trim($imagen->__toString());
								/*
								$imagen = explode("?",trim($imagen->__toString()));
								if(count($imagen) >0){
									$vehiculo["imagenes"][] =$imagen[0];
									#identificador de la imagen
									$lot['infoforauctioner']=$imagen[1]."|";
								}
								*/

							}
						}elseif($key == "extras"){
							if(count($value) >0){
								$vehiculo["extras"] .='<br><div class="img-wrapper"><img class="img-responsive"  src="/themes/carlandia/assets/features/extras.png"></div><strong>Extras</strong><br><ul> ';
								foreach( $value as $extra){
									$vehiculo["extras"].= "<li>". trim($extra->__toString()) ."</li>";
								}
								$vehiculo["extras"] .="</ul> ";
							}


						}elseif($key == "equipamientoDestacado"){
							if(count($value) >0){
								$vehiculo["extras"] .='<br><div class="img-wrapper"><img class="img-responsive"  src="/themes/carlandia/assets/features/equipamiento_destacado.png"></div><strong>Equipamiento Destacado</strong><br><ul> ';
								foreach( $value as $destacado){
									$vehiculo["extras"].= "<li>". trim($destacado->__toString()) ."</li>";
								}
								$vehiculo["extras"] .="</ul> ";
							}


						}elseif($key == "serie"){

							foreach( $value as $keyserie => $serie){
								if(count($value) >0){
									$vehiculo["extras"] .='<br><div class="img-wrapper"><img class="img-responsive"  src="/themes/carlandia/assets/features/'.strtolower($keyserie).'.png"></div><strong>'.$keyserie.'</strong><br><ul> ';
									foreach($serie as $equipo){

										$vehiculo["extras"].= "<li>". trim($equipo->__toString()) ."</li>";
									}
									$vehiculo["extras"] .="</ul> ";
								}


							}

						}elseif($key == "pinturas"){
							if(count($value) >0){
								$vehiculo["extras"] .='<br><div class="img-wrapper"><img class="img-responsive"  src="/themes/carlandia/assets/features/pintura.png"></div><strong>Pintura</strong><br><ul> ';
								foreach( $value as $pintura){
									$vehiculo["extras"].= "<li> Pintura ". trim($pintura->__toString()) ."</li>";
								}
								$vehiculo["extras"] .="</ul> ";
							}
						}
						elseif($key == "tapicerias"){
							if(count($value) >0){
								$vehiculo["extras"] .='<br><div class="img-wrapper"><img class="img-responsive"  src="/themes/carlandia/assets/features/tapiceria.png"></div><strong>Tapicería</strong><br><ul> ';
								foreach( $value as $tapizado){
									$vehiculo["extras"].= "<li> Tapizado ". trim($tapizado->__toString()) ."</li>";
								}
								$vehiculo["extras"] .="</ul> ";
							}
						}
						elseif($key == "consumo"){
							foreach( $value as $keyConsumo =>$consumo){
								$vehiculo["consumo_".$keyConsumo]= trim($consumo->__toString());
							}

						}
						elseif($key == "datosLote"){
							foreach( $value as $keyLote =>$valLot){
								$vehiculo[$keyLote] = trim($valLot->__toString());
							}

						}

						else{
							$vehiculo[$key] = trim($value->__toString());
						}
					}


					$carlandiaCommission = \Config::get("app.carlandiaCommission");
					#numero por el que multiplicaremos los precios para que se sume la comision de carlandia
					$factorcomision = 1 + $carlandiaCommission;
					if($vehiculo["tipo_venta"] == "directa" ){
						$idAuction = self::VENTA;
						$lot['startprice'] = $this->redondeoCarlandia(ceil($vehiculo['precio_venta_directa'] * $factorcomision));
						# 20-04-2022 no debe hacerse el redondeo de carlandia ya que lo han pedido así
						$lot['lowprice'] = ceil($vehiculo['precio_venta_directa_minimo'] * $factorcomision);

					}else{

						$idAuction = self::ONLINE;
						$lot['startprice'] = $this->redondeoCarlandia(ceil($vehiculo['precio_subasta_salida'] * $factorcomision));

						$lot["highprice"] = $this->redondeoCarlandia(ceil($vehiculo['precio_subasta_compra_ya'] * $factorcomision));
						$lot["reserveprice"] = $this->redondeoCarlandia(ceil($vehiculo['precio_subasta_reserva'] * $factorcomision));
					}

					if(empty($lot['startprice'])){
						echo "Saltado no tiene precio ".$lot['startprice'];
						continue;
					}

					#guardamos el precio promedio para poderlo comparar y  saber si el vehículo tiene un buen precio, si no hay precio ponemos el del producto tenia en la web de motorflash para que no falle la query
					$lot['costprice'] = $vehiculo['precio_estimacion'] ?? $vehiculo['precio'] ;
					#evitamos que venga un cero, ya que la condicion con los interrogantes no salta si es un cero
					if(empty($lot['costprice'] )){
						$lot['costprice']=$lot['startprice'];
					}

					#la subasta de venta directa tambien se finalizará en la fecha, el lote dejerá de poder verse
					$lot["enddate"] = substr($vehiculo['fecha_fin_subasta'],0,10); #substr($vehiculo['fecha_fin_subasta'], 0,10)
					$lot["endhour"] = substr($vehiculo['fecha_fin_subasta'],-8);

					#EN PRUEBAS NO GUARDARÉ LAS FECHAS QUE NOS PASAN PARA QUE NO CADUQUEN LOS LOTES
					if ( env('APP_DEBUG')) {
						$lot["enddate"] = "2022-12-31";
						$lot["endhour"] = "17:00:00";

					}


					//$lot = $this->createLotObject($vehiculo, $fields);

					$lot["owner"] = $this->codCli;
					#Debemos activar un lote si ya estaba retirado con valor E, como ellos se encargan de que un lote retirado por ellos no vuelva a venir con el mismo valor de lote
					$lot["retired"] = "N";

					$lot["idauction"] = $idAuction;
					#AHORA EL IDORIGEN NECESITARÁ PONER EL IDLOTE
					$lot['idorigin'] = $idAuction."-".$vehiculo['motorflashID']."-".$vehiculo['idLote']."-".$this->codCli;
					$lot['reflot'] =$vehiculo['idLote'];

					#obtenemos el año de matriculacion
					$fechaMatriculacion = explode("/",$vehiculo['fecha_matriculacion']);
					if(count($fechaMatriculacion) == 3){
						$vehiculo['ano_matriculacion'] = trim($fechaMatriculacion[2]);

					}

					$idCategory = $this->getIdCategory($vehiculo["marca"]);
					#marca será tambien la seccion, ya que el modelo podría provocar un problema por la limitación de secciones que puede llegar a haber
					$lot['idsubcategory'] = $this->getIdSubCategory($idCategory, $vehiculo["modelo"]);

					#creamos un titulo con campos
					$lot['title'] = $vehiculo["marca"]." ".$vehiculo["modelo"]." ". $vehiculo["version"];

					#han llegado vehículos sin título y la API hechaba para atras toda la carga, si no hay titiulo saltamos el vehículo
					if(empty(trim($lot['title']))){
						echo "Saltado ".$vehiculo['motorflashID']."no tiene marca ni modelo";
						continue;
					}

					#no puede venir vacia la descirpcion, si no falla la API
					$lot['description'] = $lot['title'];
					$lot['search'] = $lot['description'];
					$lot['extrainfo'] = $vehiculo["extras"]??'';


					#$lot = $this->subastaController->addFgSubDates($lot, $this->datesFgSub, $addTime);
					/*
					if($this->datesFgSub->tipo_sub == 'O'){
						$addTime += Config::get('app.increment_endlot_online', 60);
					}
					*/
					#caracteristicas
					$lot['features'] = $this->setFeatures($featuresList, $vehiculo);

					$exist = (array_key_exists($lot['idorigin'], $this->idOrigins[$idAuction])) ? true : false;


					#IMAGENES
					#Cojo el númro de la imagen de ellos (01, 02) y le resto 1 para obtener nuestro orden


					$imagesNuevas = array();
					$imagesTodas = array();
					$idImgNuevas=[];
					$imgOld=array();
					#cargamos el listado de imágenes de la anteriorcarga que se guardó en base de datos
					if($exist){
						#si no hay nada, no hacemos el explode, ya que el explode generasi o si un array de un elemento aunque no haya nada
						if( !empty($this->idOrigins[$idAuction][$lot['idorigin']]["infotr_hces1"])){
							$imgOld = explode("|",$this->idOrigins[$idAuction][$lot['idorigin']]["infotr_hces1"]);
						}

					}

					foreach ($vehiculo["imagenes"] as $key => $img) {

						$imagen = explode("?",$img);
						if(count($imagen) >0){
							$idImageMF = $imagen[1];
							#guardamos todas las imagenes que vienen para que quede registrado en la base de datos, luego las concatenaremos en un string
							$idImgNuevas[] = $idImageMF ;


							#generamos la estructura de la imagen y se guardara en dos array distintos, el de todas y el de las nuevas, dependiendo de los cambios cogeremos uno u otro
							$ordenTmp = substr(trim($imagen[0]),-6);
							$orden = substr($ordenTmp,0,2) - 1;
							$item = array();
							$item['idoriginlot'] = $lot['idorigin'];
							$item['order'] = $orden;
							$item['img'] = trim( $imagen[0]);

							$imagesTodas[] = $item;
							#miramos si existe la imagen y si ya existe la vaciamos del array, las imagenes que queden en el array son imagenes que existian en la anterior carga pero en esta no. Es decir que las han borrado en su sistema

							if(in_array($idImageMF, $imgOld)){
								$iArrayImg = array_search($idImageMF, $imgOld);
								unset($imgOld[$iArrayImg]);
							}else{
								$imagesNuevas[]=$item;
							}
						}
					}

					#guardamos todas los id de las imagenes que vienen para que quede registrado en la base de datos
					$lot['infoforauctioner'] = implode("|", $idImgNuevas);


					#si quedan imagenes antiguas, debemos generar todas las que vienen de nuevo, ya que han podido cambiar orden o eliminar algunas
					#comento para que no se carguen las fotos

					if(count($imgOld)>0){
						$imagenes[$lot['idorigin']]["images"] = $imagesTodas;
						$imagenes[$lot['idorigin']]["delete"] = true;


					}elseif(count($imagesNuevas)>0){
						$imagenes[$lot['idorigin']]["images"] = $imagesNuevas;
						$imagenes[$lot['idorigin']]["delete"] = false;

					}

					if ($exist) {

						$lot['reflot'] = $this->idOrigins[$idAuction][$lot['idorigin']]["ref_asigl0"];

						/* HORA NO QUIEREN ESTO lO HA PEDIDO SERGI DE QUITAR DESPUES DE HABLAR CON jUANCARLOS 15-11-2021
						#crear puja justo inferior al precio de reserva si es necesario
						if(!empty($lot["reserveprice"])){
							#si se reactiva faltaria hacer que haga tambien la puja
							$this->ordenReserva($idAuction,$lot['reflot'] , $lot["reserveprice"]);
						}
						*/
						$updateLots[$idAuction][] = $lot;

						#eliminamos el vehículo del listado, ya que los que queden los retiraremos
						unset($this->idOrigins[$idAuction][$lot['idorigin']]);

					} else {



						/* HORA NO QUIEREN ESTO lO HA PEDIDO SERGI DE QUITAR DESPUES DE HABLAR CON jUANCARLOS 15-11-2021
						#crear orden justo inferior al precio de reserva si es necesario
						if(!empty($lot["reserveprice"])){
								#si se reactiva faltaria hacer que haga tambien la puja
							$this->ordenReserva($idAuction,$lot['reflot'] , $lot["reserveprice"]);
						}
						*/
						$newLots[$idAuction][] = $lot;
					}







		}

		$lotControler = new LotController();

		# LOTES ONLINE
		if (!empty($newLots[self::ONLINE])) {

			$json = $lotControler->createLot($newLots[self::ONLINE]);

			$result = json_decode($json);

			if ($result->status == 'ERROR') {
				return $json;
			}
		}


		if (!empty($updateLots[self::ONLINE])) {

			$json = $lotControler->updateLot($updateLots[self::ONLINE]);
			$result = json_decode($json);

			if ($result->status == 'ERROR') {
				return $json;
			}
		}

		# LOTES TIENDA
		if (!empty($newLots[self::VENTA])) {

			$json = $lotControler->createLot($newLots[self::VENTA]);

			$result = json_decode($json);

			if ($result->status == 'ERROR') {
				return $json;
			}
		}

		if (!empty($updateLots[self::VENTA])) {

			$json = $lotControler->updateLot($updateLots[self::VENTA]);
			$result = json_decode($json);

			if ($result->status == 'ERROR') {
				return $json;
			}
		}

		#retirear lotes que aun no esten retirados y no vengan en el xml
		$this->retirarLotes();

		#lanza las consultas a motorflash de si los precios son válidos si no estamos en pruebas
		if(!env('APP_DEBUG')){
			$this->requestProvider($this->codCli);
		}


		#ENVIAR JOB DE IMAGENES
		#las enviamos al final para que ya esten generados los lotes así solo se generan si existen los lotes.
		if(!env('APP_DEBUG')){

			foreach($imagenes as $imagenesLot){
				#hacemos un job por cada lote

				ImgApiJob::dispatch($imagenesLot["images"],$imagenesLot["delete"])->onQueue(env('QUEUE_IMG_ENV','imagesPRE'));
			}
		}
	}

	public function retirarLotes(){
		$retirar = array();

		$retirar[0] = array();
		$cont=0;
		$i=0;
		foreach($this->idOrigins as $subasta){
			foreach($subasta as $lote){
				$cont++;
				#no se pueden pasar mas de 1000 valores en un where in , por eso antes de llegar a mil creamos otro array.
				if($cont>=1000){
					$i++;
					$cont=0;
					$retirar[$i]=array();
				}
				if($lote["retirado_asigl0"] == 'N'){
					$retirar[$i][]= $lote["idorigen_hces1"];
				}
			}
		}

		foreach($retirar as $idRetirar){
			FgAsigl0::wherein("idorigen_asigl0",$idRetirar)->update(["retirado_asigl0"=>"S"]);
		}

	}

	public function requestProvider($idProvider){
		$lotes = FgAsigl0::select("NUM_HCES1, LIN_HCES1, IDORIGEN_HCES1, IMPSALHCES_ASIGL0 ")->JoinFghces1Asigl0()
		->where("prop_hces1",$idProvider)->where("retirado_asigl0","N")->get();
		foreach($lotes as $lote){
			UniversalJob::dispatch("App\Http\Controllers\\externalws\motorflash\\ValidateLotController", "requestLot", $lote->toarray())->onQueue(env('QUEUE_IMG_ENV','imagesPRE'));
		}

	}

	#realizar orden si hay un precio de reserva y no hay ordenes
	public function ordenReserva($codSub,$ref, $precioReserva){
		$subasta = new Subasta();
		$subasta->cod = $codSub;
		#$pujaReserva = $subasta->pujaAnterior($precioReserva, $this->scaleRanges);
		#la puja reserva debe justo inferior al preci ode reserva ya que las pujas son en firme
		$pujaReserva =$precioReserva-1;

		#sacamos por separado el importe máximo y la linea máxima
		$order = FgOrlic::select("max(HIMP_ORLIC) IMP")->where("SUB_ORLIC", $codSub)->where("REF_ORLIC", $ref)->first();

		# si las pujas son inferiores a la que queremos crear la creamos, si fueran iguales o superiores ya no haria falta
		if(empty($order) || $order->imp < $pujaReserva){
            $subasta->ref = $ref;
            $subasta->licit = 1;
            $subasta->imp = $pujaReserva;
            $subasta->type_bid = "R";
			$subasta->addOrden();
		}

	}


/*

	public function __construct_DAPDA($idAuction){


		//	PUNTUALIZACIONES:
		//	Van a existir solo 2 subastas una de venta directa y otra online
		//	las carga se realiza por cada cliente que es cedente, por lo que debemos saber que vehículos ya existen de ese concesionario




		$this->idAuction = $idAuction;
		#$this->datesFgSub = FgSub::select('dfec_sub', 'dhora_sub', 'hfec_sub', 'hhora_sub', 'tipo_sub')->where('cod_sub', $idAuction)->first();

		#cargamos las caracteristicas y las caracteriticas value
		$this->subastaController = new SubastaController();
		$this->subastaController->fgCaracteristicas = FgCaracteristicas::select('name_caracteristicas', 'id_caracteristicas', 'filtro_caracteristicas')->get();
		$this->subastaController->featureValues = $this->subastaController->existingFeatureValues();

		$this->ref=FgAsigl0::select(" nvl(max(ref_asigl0),0) as ref")->where("sub_asigl0", $idAuction)->first()->ref;
		$this->idOrigins=self::existingLots($idAuction);
		$this->categories = self::existingCategories();
		$this->subCategories = self::existingSubCategories();

	}


	public function loadDapda($xml){
		$featuresList = array("version" => "versión", "km"=>"km", "hp" => "hp", "cilindrada" => "cilindrada", "puertas" => "puertas", "garantia" => "garantía", "year" => "año de fabricación", "combustible" => "combustible", "cambio" => "cambio", "color" => "color", "metalizado" => "metalizado", "carroceria" => "carroceria", "localidad" =>"ubicación", "modelo" =>"modelo" );


		$addTime = 0;
		$newLots = array();
		$updateLots = array();
		#cogemos el ultimo ref

		$imagenes = array();

		foreach($xml->concesionario as $concesionario){

			if(!empty($concesionario->vehiculo)){
				foreach($concesionario->vehiculo as $vehiculoTmp){
					#pasamos los valores del xml a un array
					$vehiculo = array();

					$vehiculo["localidad"] = trim($concesionario->localidad->__toString());
					foreach($vehiculoTmp as $key => $value){
						$vehiculo["extras"] ="";
						if($key == "imagenes"){
							$vehiculo["imagenes"] = array();
							foreach( $value as $imagen){
								$vehiculo["imagenes"][] =trim($imagen->linkimagen->__toString());
							}
						}elseif($key == "extras"){
							$vehiculo["extras"] = "<ul>";
							foreach( $value as $extra){
								$vehiculo["extras"].= "<li>". trim($extra->__toString()) ."</li>";
							}
							$vehiculo["extras"] .= "</ul>";
						}else{
							$vehiculo[$key] = trim($value->__toString());
						}

					}


					//$lot = $this->createLotObject($vehiculo, $fields);
					#hacemos que el id del lote sea único por subasta
					$lot["idauction"] = $this->idAuction;
					$lot['idorigin'] = $this->idAuction."-".$vehiculo['externalid'];

					$lot['startprice'] = $vehiculo['precio'];

					$idCategory = $this->getIdCategory($vehiculo["marca"]);
					#marca será tambien la seccion, ya que el modelo podría provocar un problema por la limitación de secciones que puede llegar a haber
					$lot['idsubcategory'] = $this->getIdSubCategory($idCategory, $vehiculo["marca"]);

					#creamos un titulo con campos
					$lot['title'] = $vehiculo["marca"]." ".$vehiculo["modelo"]." ". $vehiculo["version"];
					#no puede venir vacia la descirpcion, si no falla la API
					$lot['description'] = $lot['title'];
					$lot['extrainfo'] = $vehiculo["extras"]??
					$lot['idauction'] = $this->idAuction;


					//$lot = $this->subastaController->addFgSubDates($lot, $this->datesFgSub, $addTime);

					//if($this->datesFgSub->tipo_sub == 'O'){
					//	$addTime += Config::get('app.increment_endlot_online', 60);
					//}

					#caracteristicas
					$lot['features'] = $this->setFeatures($featuresList, $vehiculo);

					$exist = (array_key_exists($lot['idorigin'], $this->idOrigins)) ? true : false;

					if ($exist) {

						$lot['reflot'] = $this->idOrigins[$lot['idorigin']]["ref_asigl0"];
						$updateLots[] = $lot;
					} else {
						$this->ref++;

						$lot['reflot'] =$this->ref;

						$newLots[] = $lot;
					}


					#IMAGENES
					foreach ($vehiculo["imagenes"] as $key => $img) {
						$item = array();
						$item['idoriginlot'] = $lot['idorigin'];
						$item['order'] = $key;
						$item['img'] = trim($img);

						$imagenes[] = $item;
					}
				}
			}
		}

		$lotControler = new LotController();


		if (!empty($newLots)) {

			$json = $lotControler->createLot($newLots);

			$result = json_decode($json);

			if ($result->status == 'ERROR') {
				return $json;
			}
		}

		if (!empty($updateLots)) {

			$json = $lotControler->updateLot($updateLots);
			$result = json_decode($json);

			if ($result->status == 'ERROR') {
				return $json;
			}
		}

		return response($imagenes, 200);

	}
*/
	public function setFeatures($featuresList, $vehiculo){
		$features = array();

		foreach($featuresList as $key => $feature){

			if(!empty($vehiculo[$key])){
				$featureProperties = $this->subastaController->addFeaturePorperty($feature, $vehiculo[$key], $this->subastaController->featureValues);

				if(!empty($featureProperties)){
					$features[$feature] = $featureProperties;
				}

			}

		}

		return $features;
	}



	static function existingCategories() {
		$categoriesTemp = FgOrtsec0::select("LIN_ORTSEC0,DES_ORTSEC0")->where("SUB_ORTSEC0", 0)->orderby("des_ortsec0")->get();
		$categories = array();
		foreach($categoriesTemp as $category){
			$categories[mb_strtolower($category->des_ortsec0)] = $category->lin_ortsec0;
		}
		return $categories;
	}

	static function existingSubCategories() {
		$subCategoriesTemp = fgOrtsec1::select("LIN_ORTSEC1, DES_SEC, COD_SEC")->where("SUB_ORTSEC1", 0)->JoinFxSec()->get();
		$subCategories = array();
		foreach($subCategoriesTemp as $subCategory){
			if(empty($subCategories[$subCategory->lin_ortsec1])){
				$subCategories[$subCategory->lin_ortsec1] = array();
			}
			$des_sec = mb_strtolower($subCategory->des_sec);
			$subCategories[$subCategory->lin_ortsec1][$des_sec] = $subCategory->cod_sec;

		}
		return $subCategories;
	}

	public function getIdCategory($desCategory){
		$desCategoryLower = mb_strtolower($desCategory);
		if (array_key_exists($desCategoryLower, $this->categories)){
			return $this->categories[$desCategoryLower];
		}else{
			$newId = $this->createCategory($desCategory);
			#añadimos la nueva categoria, por si otro vehiculo la tueviera
			$this->categories[$desCategoryLower] = $newId;

			return $newId;
		}

	}

	public function getIdSubCategory($idCategory, $desSubCategory){
		$desSubCategoryLower = mb_strtolower($desSubCategory);


		if (array_key_exists($idCategory, $this->subCategories)  &&  array_key_exists($desSubCategoryLower, $this->subCategories[$idCategory])){
			return $this->subCategories[$idCategory][$desSubCategoryLower];
		}else{
			$newId = $this->createSubCategory($idCategory, $desSubCategory);
			#añadimos la nueva subCategoria, por si otro vehiculo la tuviera
			if(!array_key_exists($idCategory, $this->subCategories)){
				$this->subCategories[$idCategory] = array();
			}
			$this->subCategories[$idCategory][$desSubCategoryLower] = $newId;
			return $newId;
		}

	}

	public function createCategory($desCategory){
		$id = FgOrtsec0::select(" nvl(max(LIN_ORTSEC0)+1,1) id ")->where("SUB_ORTSEC0", 0)->orderby("des_ortsec0")->first()->id;
		$fields = array("lin_ortsec0" => $id,"sub_ortsec0" => 0, "orden_ortsec0" => 1, "des_ortsec0" => $desCategory, "key_ortsec0" => \Str::slug($desCategory));
		FgOrtsec0::create($fields);
		return $id;
	}
	public function createSubCategory($idCategory,$desCategory){

		$cod_sec=FxSec::newCodSec();
		FxSec::create(["cod_sec" => $cod_sec, "des_sec" =>$desCategory , "key_sec" =>\Str::slug($desCategory)]);
		$fields = array("lin_ortsec1" => $idCategory,"sub_ortsec1" => 0, "orden_ortsec1" => 1, "sec_ortsec1" => $cod_sec);
		FgOrtsec1::create($fields);
		return $cod_sec;
	}

	static function existingLots($idAuction) {
		$idOriginsTemp = FgAsigl0::select('IDORIGEN_HCES1','REF_ASIGL0')
						->joinFghces1Asigl0()
						->where('SUB_ASIGL0', $idAuction)
						->get()->ToArray();

		$idOrigins = [];
		foreach ($idOriginsTemp as $key => $value) {
			$idOrigins[$value['idorigen_hces1']] = $value;
		}
		return $idOrigins;
	}

	 function existingLotsCedente($codCli) {
		$idOriginsTemp = FgAsigl0::select('IDORIGEN_HCES1','SUB_ASIGL0','REF_ASIGL0', 'RETIRADO_ASIGL0', 'INFOTR_HCES1')
						->joinFghces1Asigl0()
						->where('PROP_HCES1', $codCli)
						->get()->ToArray();

		$idOrigins = [];
		foreach($this->idAuctions as $codAuction){
			$idOrigins[$codAuction] = [] ;
		}
		foreach ($idOriginsTemp as $key => $value) {
			$idOrigins[$value['sub_asigl0']][$value['idorigen_hces1']] = $value;

		}
		return $idOrigins;
	}


	function redondeoCarlandia($num){
		$mod = $num % 100;
		if($mod > 0 && $mod < 50){
			$num += 50-$mod;
		}elseif($mod>50 && $mod<100){
			$num += 100-$mod;
		}
		return $num;
	}
}
