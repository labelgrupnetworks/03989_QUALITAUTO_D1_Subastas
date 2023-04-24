<?php

namespace App\Http\Controllers\apilabel;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\apilabel\WebApiUser;
use App\Http\Controllers\apilabel\CategoryController;

//opcional
use App;
use Request;
use DB;
use Log;
use GuzzleHttp\Client;
use stdClass;

class test extends BaseController
{

    public function index(){
        #si el test mode es api llamamos al la api, si es CONTROLLER  llamamos internamente al controlador
        $this->testMode=request("testmode","JSON"); //"API";
        $function = request("function");

        if(!empty($function)){
            $this->{$function}();
        }else{
            echo "indica funcion usando la variable 'function'";
        }

	}

	#Bidders (licitadores)
	#http://www.newsubastas.test/apilabel/test?function=postBidder&testmode=CONTROLLER
	public function postBidder(){

		$bidder = new stdClass();
		$bidder->idoriginclient = "3000000";
		$bidder->codbidder = "2";
		$bidder->idauction = "SUBALO";

		$items[] = (array)$bidder;
		#los dos últimos parametros son solo para lanzar los controladores
		$this->excuteMode($items,"POST","bidder", new BidderController(false),"createBidder");


	}

	#http://www.newsubastas.test/apilabel/test?function=getBidder&testmode=CONTROLLER
	public function getBidder(){
		$parameters = array("idauction" => "00000153", "codbidder" => "37");
		$this->excuteMode($parameters,"GET","bidder", new BidderController(false),"showBidder");
	}

	#http://www.newsubastas.test/apilabel/test?function=deleteBidder&testmode=CONTROLLER
	public function deleteBidder(){
		$parameters = array("idauction" => "582", "codbidder" => "1");
		$this->excuteMode($parameters,"DELETE","bidder", new BidderController(false),"eraseBidder");
	}






	#PAYMENT
	#http://www.newsubastas.test/apilabel/test?function=postPayment&testmode=CONTROLLER
	public function postPayment(){
				$pdf = "https://auctions-duran.enpreproduccion.com/arte.pdf";
				$pdf64 = "https://auctions-duran.enpreproduccion.com/portadalibro.pdf";



				$Payment = new stdClass();
				$Payment->idorigincli = "21125";  // "76355";
				$Payment->paid = "N";
				$Payment->serial = "T20";
				$Payment->number = 3;
				$Payment->amount = 2;
				$Payment->reason = "M";
				$Payment->description = "Compras subasta 150 5, 6 y 7 de Julio 2022";
				$Payment->date =  "2023-02-20 19:34:24";//  date("Y-m-d H:i:s");
				$Payment->pdf = null;
				//$Payment->pdf64 =base64_encode(file_get_contents( $pdf64));
				$items[] = (array)$Payment;

				#los dos últimos parametros son solo para lanzar los controladores
				$this->excuteMode($items,"POST","payment", new PaymentController(false),"createPayment");


			}

	#http://www.newsubastas.test/apilabel/test?function=getPayment&testmode=CONTROLLER
	public function getPayment(){
		$parameters = array("serial" => "T20", "number" => "53");
		$this->excuteMode($parameters,"GET","payment", new PaymentController(false),"showPayment");
	}


	#http://www.newsubastas.test/apilabel/test?function=deletePayment&testmode=CONTROLLER
	public function deletePayment(){
		$parameters = array("serial" => "T20", "number" => "20230005");
		$this->excuteMode($parameters,"DELETE","payment", new PaymentController(false),"erasePayment");
	}


	#http://www.newsubastas.test/apilabel/test?function=putPayment&testmode=CONTROLLER
	public function putPayment(){
		//$pdf = "https://auctions-duran.enpreproduccion.com/arte.pdf";
		//$pdf64 = "https://auctions-duran.enpreproduccion.com/portadalibro.pdf";
		$Payment = new stdClass();
		$Payment->idorigincli = "281323";
		$Payment->paid = "S";
		$Payment->serial = "T20";
		$Payment->number = 13281323;
		//$Payment->amount = 330;
		//$Payment->description = "prueba de pago 330 €";
		//$Payment->date = date("Y-m-d H:i:s"); // "2020-06-23 12:14:10";
	//	$Payment->pdf64 = base64_encode(file_get_contents( $pdf64));
		//$Payment->pdf = $pdf;

		$items[] = (array)$Payment;


		#los dos últimos parametros son solo para lanzar los controladores
		$this->excuteMode($items,"PUT","payment", new PaymentController(false),"updatePayment");



	}

	#BIDS
	#http://www.newsubastas.test/apilabel/test?function=getBid&testmode=CONTROLLER
	public function getBid(){
		$parameters=array("idauction" => "00001009"); //, "min_date" => "2020-05-15 00:00:00", "max_date" => "2020-06-05 00:00:00" , "idoriginlot" => "2pa" , "idoriginclient" => "90045"
		#, "idoriginlot" => "Origen5", "idoriginclient" => 45964
		$this->excuteMode($parameters,"GET","bid", new BidController(false),"showBid");
	}


	#AUCTION
	#http://www.newsubastas.test/apilabel/test?function=postAuction&testmode=CONTROLLER
	public function postAuction(){

				#añado campos como si hicieramos desde admin

				$Auction = new stdClass();
				$Auction->idauction = "testapi";
				$Auction->name = "Subasta de test active";
				$Auction->type = "W";
				$Auction->status = "S";
				$Auction->description = "Subastas description";
				#$Auction->visiblebids = "N";
				$Auction->startauction = "2020-10-30 16:00:00";
				$Auction->finishauction = "2020-10-30 23:05:00";
				$Auction->startorders = "2020-10-15 11:00:00";
				$Auction->finishorders = "2020-10-30 12:00:00";

				#idiomas Subastas
				$auctionLang = new stdclass();
				$auctionLang->lang = "en";
				$auctionLang->name = "name ingles";
				$auctionLang->description = "description ingles";
				$auctionLang->metatitle = "metatitle ingles";
				$auctionLang->metadescription = "metadescription ingles";
				$Auction->auctionlanguages[]=(array)$auctionLang;


				$Auction->sessions = array();

				# array(  "start"=> "start", "finish" => "end", "startorders"=> "orders_start", "finishorders" => "orders_end", "firstlot" => "init_lot", "endlot" => "endlot"   );

				$session = new stdClass();

				$session->name = "Subasta de api session";
				$session->reference = "001";
				$session->description = "descripcion session";
				$session->start = "2020-06-30 08:20:00";
				$session->finish = "2020-06-30 23:20:00";
				$session->startorders = "2020-06-30 23:20:00";
				$session->finishorders = "2020-06-30 23:20:00";
				$session->firstlot = 1;
				$session->lastlot = 999999;

					#idiomas sesiones
					$sessionLang = new stdclass();
					$sessionLang->lang = "en";
					$sessionLang->name = "session name ingles";
					$sessionLang->description = "session description ingles";
					$session->sessionLanguages[]=(array)$sessionLang;

				$Auction->sessions[] = (array)$session;



/*
				$session = new stdClass();

				$session->name = "Subasta de api session 2";
				$session->reference = "002";
				$session->description = "descripcion session 2";
				$session->start = "2020-06-30 08:21:00";
				$session->finish = "2020-06-30 23:21:00";
				$session->startorders = "2020-06-30 07:21:00";
				$session->finishorders = "2020-06-30 23:21:00";
				$session->firstlot = 50;
				$session->lastlot = 500;

				$Auction->sessions[] = (array)$session;
*/

				$items[] = (array)$Auction;


				#los dos últimos parametros son solo para lanzar los controladores
				$this->excuteMode($items,"POST","auction", new AuctionController(false),"createAuction");



			}

			#http://www.newsubastas.test/apilabel/test?function=getAuction&testmode=CONTROLLER
	 public function getAuction(){
		$parameters=array("idauction" => "20211100" );
		$this->excuteMode($parameters,"GET","auction", new AuctionController(false),"showAuction");
	}


	#http://www.newsubastas.test/apilabel/test?function=putAuction&testmode=CONTROLLER
	public function putAuction(){

		#añado campos como si hicieramos desde admin

		$Auction = new stdClass();
		$Auction->idauction = "testapi";






		$auctionLang = new stdclass();
		$auctionLang->lang = "fr";
		$auctionLang->name = "name fr";
		$auctionLang->description = "description fr";
		$auctionLang->metatitle = "metatitle fr";
		$auctionLang->metadescription = "metadescription fr";
		$Auction->auctionlanguages[]=(array)$auctionLang;

		$auctionLang = new stdclass();
		$auctionLang->lang = "en";
		$auctionLang->name = "name en";
		$auctionLang->description = "description en";
		$auctionLang->metatitle = "metatitle en";
		$auctionLang->metadescription = "metadescription en";
		$Auction->auctionlanguages[]=(array)$auctionLang;

		$session = new stdClass();

		$session->name = "Subasta de api session";
		$session->reference = "001";
		$session->description = "descripcion session";
		$session->start = "2020-06-30 08:20:00";
		$session->finish = "2020-06-30 23:20:00";
		$session->startorders = "2020-06-30 23:20:00";
		$session->finishorders = "2020-06-30 23:20:00";
		$session->firstlot = 1;
		$session->lastlot = 999999;

			#idiomas sesiones
			$sessionLang = new stdclass();
			$sessionLang->lang = "fr";
			$sessionLang->name = "session name fr";
			$sessionLang->description = "session description fr";
			$session->sessionLanguages[]=(array)$sessionLang;
/*
			#idiomas sesiones
			$sessionLang = new stdclass();
			$sessionLang->lang = "pt";
			$sessionLang->name = "session name pt";
			$sessionLang->description = "session description pt";
			$session->sessionLanguages[]=(array)$sessionLang;
*/
		$Auction->sessions[] = (array)$session;
/*
			$session = new stdClass();
			$session->reference = "001";
			$session->name = "SUBASTA 900 - PINTURA";
			$session->description = "SUBASTA 900 - PINTURA. Inicio subasta: 07\/06\/2021 12:00";
			$session->firstlot = 1;
			$session->lastlot = 16;
			$session->start = "2021-06-07 12:00:00";
			$session->finish = "2021-06-07 17:30:00";
			$session->finishorders = "2021-06-07 12:00:00";
			$Auction->sessions[] = (array)$session;


			$session = new stdClass();
			$session->reference = "002";
			$session->name = "SUBASTA 900 - ARTES DECORATIVAS";
			$session->description = "SUBASTA 900 - ARTES DECORATIVAS. Inicio subasta: 08\/06\/2021 13:00";
			$session->firstlot = 500;
			$session->lastlot = 514;
			$session->start = "2021-06-08 13:00:00";
			$session->finish = "2021-06-08 18:30:00";
			$session->finishorders = "2021-06-08 13:00:00";
			$Auction->sessions[] = (array)$session;


			$session = new stdClass();
			$session->reference = "003";
			$session->name = "SUBASTA 900 - JOYAS";
			$session->description = "SUBASTA 900 - JOYAS. Inicio subasta: 09\/06\/2021 14:00";
			$session->firstlot = 1100;
			$session->lastlot = 1114;
			$session->start = "2021-06-09 14:00:00";
			$session->finish = "2021-06-09 19:30:00";
			$session->finishorders = "2021-06-09 14:00:00";
			$Auction->sessions[] = (array)$session;
*/
/*
			$Auction->name = "SUBASTA DE API";
			$Auction->type = "O";
			$Auction->status = "N";
			$Auction->description = "Subastas description PUT";
			$Auction->visiblebids ="N";

			$Auction->finishorders = "2020-06-30 23:25:00";
			$Auction->sessions = array();

			# array(  "start"=> "start", "finish" => "end", "startorders"=> "orders_start", "finishorders" => "orders_end", "firstlot" => "init_lot", "endlot" => "endlot"   );

			$session = new stdClass();

			$session->name = "Subasta de api session 1";
			$session->reference = "001";
			$session->description = "descripcion session 1";
			$session->start = "2020-06-30 08:25:00";


			$session->lastlot = 49;

			$Auction->sessions[] = (array)$session;

*/

		$items[] = (array)$Auction;


		#los dos últimos parametros son solo para lanzar los controladores
		$this->excuteMode($items,"PUT","auction", new AuctionController(false),"updateAuction");



	}
		 #http://www.newsubastas.test/apilabel/test?function=deleteAward&testmode=CONTROLLER
		 public function deleteAuction(){
			$parameters=array( "reference" => "001");
			$this->excuteMode($parameters,"DELETE","auction", new AuctionController(false),"eraseAuction");
		}


	#AWARD
	#http://www.newsubastas.test/apilabel/test?function=postAward&testmode=CONTROLLER
	public function postAward(){

		$Award = new stdClass();
		$Award->idoriginlot = "740445";
		$Award->idauction = "LABELV";
		$Award->idoriginclient = '62543'; #62543
		$Award->bid = 605;
		//$Award->commission = 264;

		//$Award->invoice = "N";
		$Award->serialpay = 'L00';
		$Award->numberpay = 0;

		//$Award->date = "2020-04-29 14:02:20";


		#añado campos como si hicieramos desde admin
/*
		$Award = new stdClass();
		$Award->idoriginlot = "SS05820720463";
		$Award->idauction = "582";
		$Award->idoriginclient = 4698;
		$Award->bid = 1200;
		$Award->commission = 264;
		$Award->ref = "270";
		$Award->licit = "100107";
		$Award->clifac = "00012";
*/


		$items[] = (array)$Award;


		#los dos últimos parametros son solo para lanzar los controladores
		$this->excuteMode($items,"POST","award", new AwardController(false),"createAward");



	}

	 #http://www.newsubastas.test/apilabel/test?function=getAward&testmode=CONTROLLER
	 public function getAward(){
		$parameters=array("idauction" => "582", "idoriginlot" => "SS05820720463" );
		#, "idoriginlot" => "Origen5", "idoriginclient" => SS05820720463
		$this->excuteMode($parameters,"GET","award", new AwardController(false),"showAward");
	}

	 #http://www.newsubastas.test/apilabel/test?function=deleteAward&testmode=CONTROLLER
	public function deleteAward(){
	    $parameters=array("idauction" => "582", "idoriginlot" => "SS05820723127", "idoriginclient" => "62543");//, "idoriginlot" => "Origen20", "idoriginclient" => 45964
	 //$parameters=array("idauction" => "TIPOLOTE", "ref" => "10", "licit" => 1003);

		$this->excuteMode($parameters,"DELETE","award", new AwardController(false),"eraseAward");
	}

	#FIN AWARD



	 #CLIENT

        #http://www.newsubastas.test/apilabel/test?function=postClient&testmode=CONTROLLER
        public function postClient(){

            $Client = new stdClass();
            $Client->idorigincli = "1WW197";
			$Client->email = "PRUEBA@PRUEBA.COM";
			$Client->idnumber = "test111111111";
			$Client->name = "Ruben Sanchez Gines";
			$Client->country = "ES";
			$Client->province = "Barcelona";
			$Client->address = "Calle pichincha del Rey numero 53";
			$Client->password = "passwordhash";
			$Client->city = "viladecans";
			$Client->zipcode = "08840";
			$Client->phone = "902902902";
			$Client->mobile = "901901901";
			$Client->fax = "900900900";
			$Client->legalentity = "F";
			$Client->notes ="Buen cliente";
			#direccion envio
			$Client->countryshipping = "EN";
			$Client->namecountryshipping = "ESPAÑA";
			$Client->provinceshipping = "Barcelona2";
			$Client->addressshipping = "calle shipping";
			$Client->cityshipping = "viladecans shiping";
			$Client->zipcodeshipping = "08841";
			$Client->phoneshipping = "902902900";
			$Client->mobileshipping = "901901900";

			#no usuario web
			//$Client->notwebuser = "N";

			$items[] = (array)$Client;




            #los dos últimos parametros son solo para lanzar los controladores
            $this->excuteMode($items,"POST","client", new ClientController(false),"createClient");



        }
        #http://www.newsubastas.test/apilabel/test?function=getClient&testmode=CONTROLLER
        public function getClient(){

            $parameters=array("idorigincli"=> "000001"); //"idorigincli" => 280999
            #$parameters=array("idnumber" => "4777241SS" );
			#$parameters=array("temporaryblock" => "N");
			#$parameters=array();
            $this->excuteMode($parameters,"GET","client", new ClientController(false),"showClient");
        }
        #http://www.newsubastas.test/apilabel/test?function=putClient&testmode=CONTROLLER
        public function putClient(){
			#prueba de modificar usuario
			/*
            $Client = new stdClass();
			$Client->idorigincli = 10;
			$Client->setidorigincli ="S";
			$Client->email = "testruben@labelgrup.com";
			$Client->name = "Ruben San";
			*/
			//$Client->sendactivateemail = 'S';

			$Client = new stdClass();
			$Client->idorigincli = '21125';

			$Client->name = 'Ruben Sanchez hola';
			$Client->email = "rsanchezlabelgrup.com";
			/*
			$Client->country = 'ES';
			$Client->address = "direccion de prueba eseremso que funciona bien";
			$Client->city = "Viladecans";
			$Client->zipcode="08830";
			$Client->temporaryblock="N";
			$Client->documenttype = 2;
			$Client->docrepresentative = "123456789K";
			$Client->typerepresentative = "R";
			$Client->source = "G";
*/
            $items[] = (array)$Client;

            //simulamos que no se envia nada
            #los dos últimos parametros son solo para lanzar los controladores
            $this->excuteMode($items,"PUT","client", new ClientController(false),"updateClient");



        }
         #http://www.newsubastas.test/apilabel/test?function=deleteClient&testmode=CONTROLLER
        public function deleteClient(){
            $parameters=array("idorigincli" => "a76351");
            $this->excuteMode($parameters,"DELETE","client", new ClientController(false),"eraseClient");
        }

    #FIN CLIENT


 #Order

        #http://www.newsubastas.test/apilabel/test?function=postOrder&testmode=CONTROLLER
        public function postOrder(){
			$Order = new stdClass();
            $Order->idoriginlot = "9000046";
            $Order->idauction = "00000901";
            $Order->idoriginclient = '81675';#1WW2W3
            $Order->order = 180;
            $Order->date = "2021-12-10 00:00:00";
			$Order->num_award_conditional =1;
            $Order->lots_list_conditional = [1009,1009.1];
            /* $Order->num_award_conditional = 2;
            $Order->lots_list_conditional = [1,3.2]; */
			$Order->type = "P";
			$items[] = (array)$Order;



			/*
            $Order->phone1 = "936588211";
            $Order->phone2 = "902902902";
			$Order->phone3 = "123456789";
			*/
			#añado campos como si hicieramso desde admin
			//$Order->ref = "3";
			//$Order->licit = "15";

            #los dos últimos parametros son solo para lanzar los controladores

            $this->excuteMode($items,"POST","order", new OrderController(false),"createOrder");



        }
        #http://www.newsubastas.test/apilabel/test?function=getOrder&testmode=CONTROLLER
        public function getOrder(){
            $parameters=array("idauction" => "LABELP"); #, "type" =>"O", "min_date" => "2021-05-10 00:00"
            #, "idoriginlot" => "Origen5", "idoriginclient" => 45964
            $this->excuteMode($parameters,"GET","order", new OrderController(false),"showOrder");
        }#Y-m-d H:i:s

         #http://www.newsubastas.test/apilabel/test?function=deleteOrder&testmode=CONTROLLER
        public function deleteOrder(){
         //   $parameters=array("idauction" => "ONLINE2", "idoriginlot" => "Origen5", "idoriginclient" => 4);//, "idoriginlot" => "Origen20", "idoriginclient" => 45964
		 $parameters=array("idauction" => "ONLINE2AA", "ref" => "3", "licit" => 99915);

            $this->excuteMode($parameters,"DELETE","order", new OrderController(false),"eraseOrder");
        }

    #FIN FEATURE_VALUE


    #FeatureValue

        #http://www.newsubastas.test/apilabel/test?function=postFeatureValue&testmode=CONTROLLER
        public function postFeatureValue(){

            $featureValue = new stdClass();
            $featureValue->idfeaturevalue = 19;
            $featureValue->idfeature = 1;
            $featureValue->value = "19 castellano";

            $items[] = (array)$featureValue;

            $featureValue = new stdClass();
            $featureValue->idfeaturevalue = 19;
            $featureValue->idfeature = 10;
            $featureValue->value = "19 ingles";
            $featureValue->lang = "en";

            $items[] = (array)$featureValue;

            #los dos últimos parametros son solo para lanzar los controladores
            $this->excuteMode($items,"POST","featurevalue", new featureValueController(false),"createFeatureValue");



        }
        #http://www.newsubastas.test/apilabel/test?function=getFeatureValue&testmode=CONTROLLER
        public function getfeatureValue(){
            $parameters=array("idfeature" => "10");
            $this->excuteMode($parameters,"GET","featurevalue", new featureValueController(false),"showfeatureValue");
        }
        #http://www.newsubastas.test/apilabel/test?function=putFeatureValue&testmode=CONTROLLER
        public function putfeatureValue(){
            $featureValue = new stdClass();
            $featureValue->idfeaturevalue = 19;
            $featureValue->value = "prueba1";

            $items[] = (array)$featureValue;
			$featureValue = new stdClass();
            $featureValue->idfeaturevalue = 19;
            $featureValue->value = "prueba1";
            $featureValue->lang = "en";

            $items[] = (array)$featureValue;


            //simulamos que no se envia nada
            #los dos últimos parametros son solo para lanzar los controladores
            $this->excuteMode($items,"PUT","featurevalue", new featureValueController(false),"updatefeatureValue");



        }
         #http://www.newsubastas.test/apilabel/test?function=deleteFeatureValue&testmode=CONTROLLER
        public function deletefeatureValue(){
            $parameters=array("idfeaturevalue" => 19);
            $this->excuteMode($parameters,"DELETE","featurevalue", new featureValueController(false),"erasefeatureValue");
        }

    #FIN FEATURE_VALUE






         #IMG

        #http://www.newsubastas.test/apilabel/test?function=postImg
        public function postImg(){
            $url = "https://duran.enpreproduccion.com/img/load/real/002-4-1086.jpg";
            $url64 = "https://duran.enpreproduccion.com/img/load/real/002-4-1087.jpg";
            $img = new stdClass();
            $img->idoriginlot = "TESTWOrigen1";
            $img->img =$url  ;
            //$img->img64 =base64_encode(file_get_contents( $url64 )) ;
            $img->order=0;

            $items[] = (array)$img;

             /*
            $img = new stdClass();
            $img->idoriginlot = "Origen6";
            $img->img =$url  ;
           // $img->img64 =base64_encode(file_get_contents( $url64 )) ;
            $img->order =1;

            $items[] = (array)$img;
            */

            $this->excuteMode($items,"POST","img", new ImgController(),"createImg");
        }

        public function deleteImg(){
            $parameters=array("idoriginlot" => "Origesfsdferfen6", "order" => "0");
            $this->excuteMode($parameters,"DELETE","img", new ImgController(false),"eraseImg");
        }
 #http://www.newsubastas.test/apilabel/test?function=deleteAllImg&testmode=CONTROLLER
		public function deleteAllImg(){
            $parameters=array("idoriginlot" => "MOTORV-8306242");
            $this->excuteMode($parameters,"DELETE","img", new ImgController(false),"eraseAllImg");
        }

        # FIN IMG

     #LOTES

        #http://www.newsubastas.test/apilabel/test?function=postLot&testmode=CONTROLLER
        public function postLot(){
/*
			$a = '{"idorigin": "SS05040649395", "idauction": "504", "reflot": 24.0, "idsubcategory": "78", "title": "Lorenzo Aguirre Sánchez. \"Encierro en Pamplona\"", "description": "\"\"Encierro en Pamplona\"\". \"Encierro en Pamplona\". Óleo sobre tabla. 68 x 46. Firmado en el ángulo inferior izquierdo. Ligeras faltas de pintura. Ligera grieta en la tabla en el ángulo inferior izquierdo. 68 x 46.", "search": "Lorenzo Aguirre Sánchez. \"Encierro en Pamplona\" \"\"Encierro en Pamplona\"\". \"Encierro en Pamplona\". Óleo sobre tabla. 68 x 46. Firmado en el ángulo inferior izquierdo. Ligeras faltas de pintura. Ligera grieta en la tabla en el ángulo inferior izquierdo. 68 x 46. AGUIRRE SANCHEZ, LORENZO Si \"Encierro en Pamplona\" 68 x 46. Óleo sobre tabla Ligeras faltas 24.0", "startprice": 700.00, "buyoption": "N", "soldprice": "S", "features": [{"idfeature": 289, "idvaluefeature": 32553, "value": "AGUIRRE SANCHEZ, LORENZO"},{"idfeature": 392, "value": "Si"},{"idfeature": 393, "value": "\"Encierro en Pamplona\""},{"idfeature": 398, "value": "68 x 46."},{"idfeature": 399, "value": "Óleo sobre tabla"},{"idfeature": 401, "value": "Ligeras faltas"}], "biddercommission": 22.00, "warehouse": "3", "numberobjects": 1, "weight": 10.00, "volumetricweight": 181.00, "exportpermission": "S", "ministry": "N"}';
							//	echo $a;
			$lot = json_decode($a);
			foreach($lot->features as $key => $feature){
				$lot->features[$key] = (array) $feature;
			}
			//dd($lot);
			$items[] = (array)$lot;
			$this->excuteMode($items,"PUT","lot", new LotController(),"updatelot");
			//$this->excuteMode($items,"POST","lot", new LotController(),"createLot");






			die();
			*/
            $lot = new stdClass();
            $lot->idorigin = time();
            $lot->idauction = "TEST";
            $lot->reflot = 63;
            $lot->idsubcategory = "AV";
            $lot->title = "titulo";
            $lot->description = "Lot metadescription";
            $lot->search = "";
            $lot->startprice = 100;
            $lot->lowprice = 110;
            $lot->highprice = 150;
			$lot->reserveprice = 120;
			//$lot->originowner = "7996370";
            //$lot->retired = 'S';
            $lot->highlight = 'N';
            $lot->buyoption = 'S';
            $lot->soldprice = 'S';
           // $lot->hidden = 'S';
            $lot->disclaimed = 'S';
            $lot->startdate = '2020-03-01';
            $lot->enddate = '2020-03-01';
            $lot->starthour = '20:01:05';
            $lot->endhour = '05:40:15';
/*
			$language = array();
			$language["lang"] = "EN";
			$language["title"] = "titulo ingles";
			$language["description"] = "descripcioningles";
			$language["search"] = "busqueda ingles";
			$language["urlfriendly"] = "urlfriendly ingles";
			$lot->languages[] =  $language;

			$language = array();
			$language["lang"] = "ES";
			$language["title"] = "2titulo ";
			$language["description"] = "2descripcion";
			$language["search"] = "2busqueda ";
			$language["urlfriendly"] = "2urlfriendly ";
			$lot->languages[] =  $language;
			*/
/*
            $feature =array();
            $feature["idfeature"] =2;
            $feature["idvaluefeature"] =17;
            $feature["value"] =null;
            $lot->features[] =  $feature;
            $feature =array();
            $feature["idfeature"] =289;
            $feature["idvaluefeature"] =37086;
            $feature["value"] =null;
            $feature["lang"] ="en";
			$lot->features[] =  $feature;
			$feature =array();
            $feature["idfeature"] =3;
            $feature["value"] ="Feature";
			$lot->features[] =  $feature;
*/

            $items[] = (array)$lot;

            $this->excuteMode($items,"POST","lot", new LotController(),"createLot");
        }
		#http://www.newsubastas.test/apilabel/test?function=getLot&testmode=CONTROLLER
        public function getLot(){
            $parameters=array("idauction" =>"LABELP");
            $this->excuteMode($parameters,"GET","lot", new LotController(),"showlot");
        }
		#http://www.newsubastas.test/apilabel/test?function=putLot&testmode=CONTROLLER
        public function putLot(){
			$lot = new stdClass();
			$lot->idorigin = "1671623786";
            $lot->idauction = "TEST";
            $lot->reflot = 63;
			$lot->startdate = '2020-03-02';
            $lot->enddate = '2020-03-03';
            $lot->starthour = '20:01:04';
            $lot->endhour = '05:40:05';
           /*
            $lot->idsubcategory = "AV";
            $lot->title = "titulo";
            $lot->description = "Lot metadescription";
            $lot->search = "Texto para encontrar el lote desde el buscador";
            $lot->startprice = 100.2;
            $lot->lowprice = 110;
            $lot->highprice = 150;
			$lot->reserveprice = 120;
			//$lot->originowner = "7996370";
            //$lot->retired = 'S';
            $lot->highlight = 'N';
            $lot->buyoption = 'S';
            $lot->soldprice = 'S';
           // $lot->hidden = 'S';
            $lot->disclaimed = 'S';
            $lot->startdate = '2020-03-01';
            $lot->enddate = '2020-03-01';
            $lot->starthour = '20:01:05';
            $lot->endhour = '05:40:15';


			$language = array();
			$language["lang"] = "EN";
			$language["title"] = "2titulo ingles";
			$language["description"] = "2descripcion ingles";
			$language["search"] = "2busqueda ingles";
			$language["urlfriendly"] = "2urlfriendly ingles";
			$lot->languages[] =  $language;

			$language = array();
			$language["lang"] = "ES";
			$language["title"] = "2titulo ";
			$language["description"] = "2descripcion";
			$language["search"] = "2busqueda ";
			$language["urlfriendly"] = "2urlfriendly ";
			$lot->languages[] =  $language;
*/
			$feature =array();
			$feature["idfeature"] =289;
			$feature["idvaluefeature"] =28631;
			$feature["value"] =null;
			$lot->features[] =  $feature;
			$feature =array();
			$feature["idfeature"] =289;
			$feature["idvaluefeature"] =12951;
			$feature["value"] =null;
			$feature["lang"] ="fr";
			$lot->features[] =  $feature;
			$feature =array();
			$feature["idfeature"] =289;
			$feature["idvaluefeature"] =2755;
			$feature["value"] =null;
			$feature["lang"] ="en";
			$lot->features[] =  $feature;

			$items[] = (array)$lot;
			/*
            $lot = new stdClass();
            $lot->idorigin = "SS05820723126";
			$lot->idauction = "582";
			$lot->infoforauctioner = "información para el subastador";

			$feature =array();
			$feature["idfeature"] =289;
			$feature["idvaluefeature"] =28631;
			$feature["value"] =null;
			$lot->features[] =  $feature;
			$feature =array();
			$feature["idfeature"] =289;
			$feature["idvaluefeature"] =12951;
			$feature["value"] =null;
			$lot->features[] =  $feature;
			$feature =array();
			$feature["idfeature"] =289;
			$feature["idvaluefeature"] =2755;
			$feature["value"] =null;
			$lot->features[] =  $feature;

            $items[] = (array)$lot;
			*/
            #los dos últimos parametros son solo para lanzar los controladores
            $this->excuteMode($items,"PUT","lot", new LotController(),"updatelot");



        }

        public function deleteLot(){
            $parameters=array("idorigin" => "060521B-5");
            $this->excuteMode($parameters,"DELETE","lot", new LotController(false),"eraseLot");
        }

    #FIN LOTES


    #SUBCATEGORY

        #http://www.newsubastas.test/apilabel/test?function=postCategory
        public function postSubCategory(){

            $SubCategory = new stdClass();
            $SubCategory->idsubcategory = "AA";
            $SubCategory->idcategory = 50;
            $SubCategory->description = "Artes  Decorativas";
            $SubCategory->urlfriendly = "arte-decorativas";
            $SubCategory->order = 1;
         $SubCategory->metadescription = "Subcategory metadescription";
            $SubCategory->metatitle = " Subcategory metatitle";
          $SubCategory->metacontent = "Subcategory metacontent ";


            $items[] = (array)$SubCategory;


            $SubCategory = new stdClass();
            $SubCategory->idsubcategory = "JJ";
            $SubCategory->idcategory = 50;
            $SubCategory->description = "Joyas y Relojes";
            $SubCategory->urlfriendly = "joyas-relojes";
            $SubCategory->order = 2;
            $SubCategory->metadescription = "Subcategory metadescription";
            $SubCategory->metatitle = " Subcategory metatitle";
            $SubCategory->metacontent = "Subcategory metacontent ";

            $items[] = (array)$SubCategory;
            #los dos últimos parametros son solo para lanzar los controladores
            $this->excuteMode($items,"POST","subcategory", new SubCategoryController(false),"createSubCategory");



        }

        public function getsubCategory(){
            $parameters=array();

            $this->excuteMode($parameters,"GET","subcategory", new SubCategoryController(false),"showSubCategory");
        }

        public function putSubCategory(){
            $SubCategory = new stdClass();
            $SubCategory->idsubcategory = "JJ";
            $SubCategory->idcategory = 50;
            $SubCategory->urlfriendly = "joyas-reloj";
            $SubCategory->order = 5;

            $items[] = (array)$SubCategory;
            //simulamos que no se envia nada
            #los dos últimos parametros son solo para lanzar los controladores
            $this->excuteMode($items,"PUT","subcategory", new SubCategoryController(false),"updateSubCategory");



        }

        public function deleteSubCategory(){
            $parameters=array("idcategory" => 50, "idsubcategory" => "AA");
            $this->excuteMode($parameters,"DELETE","subcategory", new SubCategoryController(false),"eraseSubCategory");
        }

    #FIN SUBCATEGORY





    #CATEGORY

        #http://www.newsubastas.test/apilabel/test?function=postCategory
        public function postCategory(){

            $category = new stdClass();
            $category->idcategory = 1;
            $category->description = "Artes  Decorativas";
            $category->urlfriendly = "arte-decorativas";
            $category->order = null;
            /*
            $category->metadescription = "Las antigüedades tienen un magnetismo propio como fragmentos de historia y elementos únicos. La oportunidad de adquirir en subastas de artes decorativas";
            $category->metatitle = "Las antigüedades ";
            $category->metacontent = "Las antigüedades tienen un magnetismo propio como fragmentos de historia y elementos únicos. La oportunidad de adquirir en subastas de artes decorativas online o casas de subastas obras exclusivas supone el complemento decorativo perfecto y un elemento estético diferenciador. Piezas de cerámica, porcelana, plata, eboraria así como trabajos en metal de los principales talleres o manufacturas como Lalique, Meissen o Sèvres. Todas ellas con una estética y un detallismo muy cuidados que las convierte en piezas dignas de un museo de artes decorativas. eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee ";
            */

            $items[] = (array)$category;

            $category = new stdClass();
            $category->idcategory = 2;
            $category->description = "Joyas y Relojes";
            $category->urlfriendly = "joyas-relojes";
            $category->order = 2;
            /*
            $category->metadescription = "Las joyas antiguas siempre han tenido especial  consideración y se han conservado de generación en generación como un tesoro. ";
            $category->metatitle = "Las joyas antiguas siempre han tenido especial";
            $category->metacontent = "Las joyas antiguas siempre han tenido especial consideración y se han conservado de generación en generación como un tesoro. Más allá de su valor material intrínseco, las subastas de joyas online tienen un alto componente simbólico y artístico con excelentes calidades y acabados, lo que las convierte en las piezas ideales para cualquier casa de subastas. Sus elementos son de lo más diversos, aunque tradicionalmente se ha valorado el platino y el oro o la plata como materiales para las monturas y engarces, siendo los diamantes la gema fetiche. En las subastas de joyas se puede encontrar el complemento o accesorio perfecto para combinar y disfrutar diariamente o una pieza especial que hará las delicias de cualquier novia el día de su boda. En ese sentido, las diademas o tiaras y los grandes aderezos siguen siendo las piezas estrella.

            Por otro lado, también es habitual encontrar en subastas joyas online anillos, sortijas, pendientes del siglo XVIII con especial predilección por los Girandole o Pendeloques, regios conjuntos del siglo XIX en micromosaico o pelo natural así como broches de camafeos, pulseras y brazaletes, alfileres o collares y colgantes. De esta forma tendrán su lugar desde la joyería tradicional a las grandes muestras de consagradas firmas como Masriera con sus esmaltes plique-à-jour o los deslumbrantes diseños Belle Époque y Art Déco de Cartier o Boucheron.

            En el campo masculino, las subastas de relojes online se imponen con fuerza con las más diversas tipologías desde los relojes de cuerda de bolsillo a los más modernos de pulsera. En ellas se combinan los modelos clásicos de Rolex, Longines o Patek Philippe junto a las apuestas más innovadoras de marcas como Breitling o Tag Heuer. Del mismo modo, accesorios para hombre como gemelos y agujas de corbata se han convertido en un complemento indispensable de la nueva imagen del dandy cosmopolita. Por lo tanto, no lo dudes y apúntate a nuestras subastas relojes online. ";
            */
            $items[] = (array)$category;
            #los dos últimos parametros son solo para lanzar los controladores
            $this->excuteMode($items,"POST","category", new CategoryController(false),"createCategory");



        }
        public function getCategory(){
            $parameters=array();//"idcategory" => "1"
            $this->excuteMode($parameters,"GET","category", new CategoryController(false),"showCategory");
        }

        public function putCategory(){
            $category = new stdClass();
            $category->idcategory = 1;
            $category->description = "Artes y Antigüedades";
            $category->metatitle = "Las antigüedades tienen un magnetismo propio";
/*
            $category->description = "Artes ";
            $category->urlfriendly = "arte-decorativas";
            $category->order = 1;
            $category->metadescription = "Las antigüedades tienen un magnetismo propio como fragmentos de historia y elementos únicos. La oportunidad de adquirir en subastas de artes decorativas";

            $category->metacontent = "Las antigüedades tienen un magnetismo propio como fragmentos de historia y elementos únicos. La oportunidad de adquirir en subastas de artes decorativas online o casas de subastas obras exclusivas supone el complemento decorativo perfecto y un elemento estético diferenciador. Piezas de cerámica, porcelana, plata, eboraria así como trabajos en metal de los principales talleres o manufacturas como Lalique, Meissen o Sèvres. Todas ellas con una estética y un detallismo muy cuidados que las convierte en piezas dignas de un museo de artes decorativas. eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee ";
*/

            $items[] = (array)$category;
            $category = new stdClass();
            $category->idcategory = 2;
            $category->order = 3;

            /*
            $category->description = "Joyas ";
            $category->urlfriendly = "joyas";
            $category->order = 2;
            $category->metadescription = " antiguas siempre han tenido especial  consideración y se han conservado de generación en generación como un tesoro. ";
            $category->metatitle = " antiguas siempre han tenido especial";
            $category->metacontent = " antiguas siempre han tenido especial consideración y se han conservado de generación en generación como un tesoro. Más allá de su valor material intrínseco, las subastas de joyas online tienen un alto componente simbólico y artístico con excelentes calidades y acabados, lo que las convierte en las piezas ideales para cualquier casa de subastas. Sus elementos son de lo más diversos, aunque tradicionalmente se ha valorado el platino y el oro o la plata como materiales para las monturas y engarces, siendo los diamantes la gema fetiche. En las subastas de joyas se puede encontrar el complemento o accesorio perfecto para combinar y disfrutar diariamente o una pieza especial que hará las delicias de cualquier novia el día de su boda. En ese sentido, las diademas o tiaras y los grandes aderezos siguen siendo las piezas estrella.
            Por otro lado, también es habitual encontrar en subastas joyas online anillos, sortijas, pendientes del siglo XVIII con especial predilección por los Girandole o Pendeloques, regios conjuntos del siglo XIX en micromosaico o pelo natural así como broches de camafeos, pulseras y brazaletes, alfileres o collares y colgantes. De esta forma tendrán su lugar desde la joyería tradicional a las grandes muestras de consagradas firmas como Masriera con sus esmaltes plique-à-jour o los deslumbrantes diseños Belle Époque y Art Déco de Cartier o Boucheron.
            En el campo masculino, las subastas de relojes online se imponen con fuerza con las más diversas tipologías desde los relojes de cuerda de bolsillo a los más modernos de pulsera. En ellas se combinan los modelos clásicos de Rolex, Longines o Patek Philippe junto a las apuestas más innovadoras de marcas como Breitling o Tag Heuer. Del mismo modo, accesorios para hombre como gemelos y agujas de corbata se han convertido en un complemento indispensable de la nueva imagen del dandy cosmopolita. Por lo tanto, no lo dudes y apúntate a nuestras subastas relojes online. ";
*/
            $items[] = (array)$category;
            //simulamos que no se envia nada
            #los dos últimos parametros son solo para lanzar los controladores
            $this->excuteMode($items,"PUT","category", new CategoryController(false),"updateCategory");



        }

        public function deleteCategory(){
            $parameters=array("idcategory" => 1);
            $this->excuteMode($parameters,"DELETE","category", new CategoryController(false),"eraseCategory");
        }

    #FIN CATEGORY

	#DEPOSIT
	#http://www.newsubastas.test/apilabel/test?function=postDeposit&testmode=CONTROLLER
	public function postDeposit(){

		$deposit = new stdClass();
		$deposit->idoriginclient = "ruben1";
		$deposit->idauction = "LABELO";
		$deposit->idoriginlot = "LABELO-2";

		$deposit->status = "V";
		$deposit->amount = "1600";
		$deposit->date = date("Y-m-d H:i:s");// "2022-11-05 09:00:05";


		$items[] = (array)$deposit;
		#los dos últimos parametros son solo para lanzar los controladores
		$this->excuteMode($items,"POST","deposit", new DepositController(false),"createDeposit");


	}
	#http://www.newsubastas.test/apilabel/test?function=putDeposit&testmode=CONTROLLER
	public function putDeposit(){

		$deposit = new stdClass();
		$deposit->idoriginclient = "112";
		$deposit->idauction = "BBBO";
		$deposit->idoriginlot = "EJEMPLO1";
		$deposit->status = "V";
		$deposit->amount = "5000";
		$deposit->date = "2022-11-09 09:20:01";

		$items[] = (array)$deposit;
		#los dos últimos parametros son solo para lanzar los controladores
		$this->excuteMode($items,"PUT","deposit", new DepositController(false),"updateDeposit");


	}

	#http://www.newsubastas.test/apilabel/test?function=getDeposit&testmode=CONTROLLER
	public function getDeposit(){
		$parameters = array("idauction" => "BBBO", "reflot" => "20");
		$this->excuteMode($parameters,"GET","bidder", new DepositController(false),"showDeposit");
	}

	#http://www.newsubastas.test/apilabel/test?function=deleteDeposit&testmode=CONTROLLER
	public function deleteDeposit(){
		$parameters = array("idauction" => "BBBO", "idoriginlot" => "EJEMPLO1","idoriginclient" => "3");

		$this->excuteMode($parameters,"DELETE","bidder", new DepositController(false),"eraseDeposit");
	}
	#FIN DEPOSIT


    #fin de test peticiones api

    private function excuteMode($parameters, $method, $name, $controller, $function){

        echo $this->testMode."<br>";
        if($this->testMode=="JSON"){
            $this->echoJson($parameters, $method);

        }elseif($this->testMode=="CONTROLLER"){


            $res = $controller->{$function}($parameters);

            //header('Content-Type: text/html; charset=unicode');
            echo "<pre>";
           echo json_encode(json_decode($res),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        }elseif($this->testMode=="API"){
            $response =  $this->requestGuzz($method,$name,$parameters);
            echo "<pre>";
             echo json_encode(json_decode($response->getBody()),JSON_PRETTY_PRINT  | JSON_UNESCAPED_UNICODE);
        }else{
            echo "WRONG MODE ";
        }


    }


     #función que hace las llamadas a la API
     public function requestGuzz ( $method, $object, $parameters){

        $request= new stdClass();

        if($method == "POST" || $method == "PUT"){
            $request->items = $parameters;
        }else{
            $request->parameters = $parameters;
        }
        $url = \Config::get("app.url"). "/apilabel/$object";

		$clientGuzz = new Client();
		#cogemos el primer usuario que haya de la api
		$ApiUser = WebApiUser::select("LOGIN_API_USER, PASSWORD_API_USER")->first();

        return $clientGuzz->request($method, $url,[
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
              ],
               'auth' => [$ApiUser->login_api_user, $ApiUser->password_api_user],
            \GuzzleHttp\RequestOptions::JSON =>$request
            ]);
    }




    #TEST peticiones  API
    public function echoJson($parameters, $method){
        $request= new stdclass();

        if($method == "POST" || $method == "PUT"){
            $request->items = $parameters;
        }else{
            $request->parameters = $parameters;
        }
        header('Content-Type: application/json');
        //header('Content-Type: text/html; charset=unicode');
        echo "<pre>";
        echo json_encode($request,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    }

}
