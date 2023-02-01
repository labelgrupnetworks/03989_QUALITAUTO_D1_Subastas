<?php

use App\Models\V5\FgHces1Files;
use App\Models\V5\FgDeposito;

$cerrado = $lote_actual->cerrado_asigl0 == 'S'? true : false;
$cerrado_N = $lote_actual->cerrado_asigl0 == 'N'? true : false;
$hay_pujas = count($lote_actual->pujas) >0? true : false;
$devuelto= $lote_actual->cerrado_asigl0 == 'D'? true : false;
$remate = $lote_actual->remate_asigl0 =='S'? true : false;
$compra = $lote_actual->compra_asigl0 == 'S'? true : false;
$subasta_online = ($lote_actual->tipo_sub == 'P' || $lote_actual->tipo_sub == 'O')? true : false;
$subasta_venta = $lote_actual->tipo_sub == 'V' ? true : false;
$subasta_web = $lote_actual->tipo_sub == 'W' ? true : false;
$subasta_make_offer = $lote_actual->tipo_sub == 'M' ? true : false;
$subasta_inversa = $lote_actual->tipo_sub == 'I' ? true : false;
$subasta_abierta_O = $lote_actual->subabierta_sub == 'O'? true : false;
$subasta_abierta_P = $lote_actual->subabierta_sub == 'P'? true : false;
$retirado = $lote_actual->retirado_asigl0 !='N'? true : false;
$sub_historica = $lote_actual->subc_sub == 'H'? true : false;
$sub_cerrada = ($lote_actual->subc_sub != 'A'  && $lote_actual->subc_sub != 'S')? true : false;
$remate = $lote_actual->remate_asigl0 =='S'? true : false;
$awarded = \Config::get('app.awarded');
// D = factura devuelta, R = factura pedniente de devolver
$fact_devuelta = ($lote_actual->fac_hces1 == 'D' || $lote_actual->fac_hces1 == 'R') ? true : false;
$fact_N = $lote_actual->fac_hces1=='N' ? true : false;
$start_session = strtotime("now") > strtotime($lote_actual->start_session);
$end_session = strtotime("now")  > strtotime($lote_actual->end_session);

$inicio_pujas = strtotime("now") > strtotime($lote_actual->fini_asigl0);

$start_orders =strtotime("now") > strtotime($lote_actual->orders_start);
$end_orders = strtotime("now") > strtotime($lote_actual->orders_end);

$userSession = session('user');
$deposito = (new FgDeposito())->isValid($userSession['cod'] ?? null, $lote_actual->cod_sub, $lote_actual->ref_asigl0);

$files = FgHces1Files::getAllFilesByLotCanViewUser($userSession, $lote_actual->num_hces1, $lote_actual->lin_hces1, $deposito);

# listamos los recursos que se hayan puesto en la carpeta de videos para mostrarlos en la imagen principal
$resourcesList = [];
foreach( ($lote_actual->videos ?? []) as $key => $video){
	$resource=["src"=>$video, "format" => "VIDEO"];
	if (strtolower(substr($video, -4)) == ".gif" ){
		$resource  ["format"] = "GIF";
	}
	$resourcesList[] = $resource;
}
$currency = "B/.";

?>

@if(!Session::has('user') )
	<div class="container">
		<div class="row mt-5">
			<div  class="col-xs-12 ficha-nologin-nodeposito mt-5 mb-5"  >
				<h2>{{ trans(\Config::get('app.theme').'-app.lot.necesario-login') }}</h2>
			</div>
		</div>
	</div>
@elseif(!$deposito)
	<div class="container">
		<div class="row mt-5">
			<div  class="col-xs-12 ficha-nologin-nodeposito mt-5 mb-5"  >
				<h2>{{ trans(\Config::get('app.theme').'-app.lot.necesario-deposito') }}</h2>
			</div>
		</div>
	</div>

@else

<div class="ficha-content color-letter">
    <div class="container">
        <div class="row">
			<?php
					#debemos poenr el código aqui par que lo usen en diferentes includes
					if($subasta_web){
					$nameCountdown = "countdown";
					$timeCountdown = $lote_actual->start_session;
					}else if($subasta_venta){
					$nameCountdown = "countdown";
					$timeCountdown = $lote_actual->end_session;
					}else if($subasta_online){
					$nameCountdown = "countdownficha";
					$timeCountdown = $lote_actual->close_at;
					}
			?>
			<div class="col-xs-12 mt-5 mb-2">
					@include('includes.ficha.header_time')
			</div>
			<div class="col-xs-12">
				<div class="col-sm-7 col-xs-12 mt-2" style="position: relative">


					<div class="col-xs-12 no-padding description-ficha">
						<div class="titleficha col-xs-12 no-padding  secondary-color-text no-padding">
							@php
								$refLot = $lote_actual->ref_asigl0;
								#si  tiene el . decimal hay que ver si se debe separar
								if(strpos($refLot,'.')!==false){

										$refLot =str_replace(array(".1",".2",".3", ".4", ".5"), array("-A", "-B", "-C", "-D", "-E"),  $refLot);

									#si hay que recortar
								}elseif( \config::get("app.substrRef")){
									#cogemos solo los últimos x numeros, ya que se usaran hasta 9, los  primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
									#le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda
									$refLot = substr($refLot,-\config::get("app.substrRef"))+0;
								}
							@endphp
						<h1>{{ trans(\Config::get('app.theme').'-app.lot_list.reference') }} -	{{$refLot}} </h1>

						@php /*	- {!!$lote_actual->descweb_hces1 ?? $lote_actual->titulo_hces1!!} */ @endphp

						</div>
						@php /*
							<div class="col-xs-12 no-padding desc-lot-title d-flex justify-content-space-between">
									<p class="desc-lot-profile-title">{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>

							</div>
							*/ @endphp
							<div class="col-xs-12 no-padding desc-lot-profile-content">

									<?=    $lote_actual->desc_hces1  ?>


							</div>

					</div>


				</div>


    			<div class="col-sm-5 col-xs-12 content-right-ficha d-flex justify-content-space-between flex-column mt-2">

					<div class="d-flex  flex-column">
						<div class="ficha-info-title col-xs-12 no-padding">


					</div>

						<?php
						$categorys = new \App\Models\Category();
						$tipo_sec = $categorys->getSecciones($data['js_item']['lote_actual']->sec_hces1);
						?>
						@if(count($tipo_sec) !== 0)
							<div class="col-xs-12 no-padding fincha-info-cats">
								<div class="cat">{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</div>
								@foreach($tipo_sec as $sec)
									<span class="badge">{{$sec->des_tsec}}</span>
								@endforeach
							</div>
						@endif

					<div class="ficha-info-content col-xs-12 no-padding h-100 flex-column justify-content-center d-flex mt-2">

					@if(!$retirado && !$devuelto && !$fact_devuelta)
						<div class="ficha-info-items">
							<?php
								#debemos poenr el código aqui par que lo usen en diferentes includes
								if($subasta_web){
									$nameCountdown = "countdown";
									$timeCountdown = $lote_actual->start_session;
								} else if($subasta_online){
									$nameCountdown = "countdownficha";
									$timeCountdown = $lote_actual->close_at;
								}else if($subasta_inversa){
									$nameCountdown = "countdownficha";
									$timeCountdown = $lote_actual->close_at;
								}else{
									$nameCountdown = "countdown";
									$timeCountdown = $lote_actual->end_session;
								}
							?>

									{{-- Parte derecha --}}
									@if ($sub_cerrada)
										@include('includes.ficha.pujas_ficha_cerrada')

									@elseif($subasta_venta && !$cerrado && !$end_session)
										@if( \Config::get("app.shoppingCart") )
											@include('includes.ficha.pujas_ficha_ShoppingCart')
										@else

											@include('includes.ficha.pujas_ficha_V')
										@endif

									<?php //si un lote cerrado no se ha vendido se podra comprar ?>
									@elseif( ($subasta_web || $subasta_online) && $cerrado && empty($lote_actual->himp_csub) && $compra && !$fact_devuelta)

										@include('includes.ficha.pujas_ficha_V')
									<?php //si una subasta es abierta p solo entraremso a la tipo online si no esta iniciada la subasta ?>
									@elseif( ($subasta_online || ($subasta_web && $subasta_abierta_P && !$start_session)) && !$cerrado)

										@include('includes.ficha.pujas_ficha_O')

									@elseif( $subasta_web && !$cerrado)

										@include('includes.ficha.pujas_ficha_W')

									@elseif( $subasta_make_offer && !$cerrado)
										@include('includes.ficha.pujas_ficha_M')
									@else
										@include('includes.ficha.pujas_ficha_cerrada')
									@endif


						</div>
					@endif
					</div>
					@if(Session::has('user') && $deposito)
						<div class="col-xs-12 col-sm-12 no-padding">
							@if(( $subasta_online  || ($subasta_web && $subasta_abierta_P ) || $subasta_make_offer )  &&  !$retirado)
								@include('includes.ficha.history')
							@endif
						</div>
						
					@endif
				</div>
			</div>



        </div>
    </div>
</div>



<div class="container">



	<section class="container">
		<div class="row">
			<div class="col-xs-12">

				@foreach ($files as $file)
				<p>
					<a href="{{ $file->download_path }}" alt="{{ $file->name_hces1_files }}" target="_blank">
						{{ $file->name_hces1_files }}
					</a>
				</p>
				@endforeach

			</div>
		</div>
	</section>



</div>




<script>
            //Mostramos la fecha

            $("#cierre_lote").html(format_date_large(new Date("{{$timeCountdown}}".replace(/-/g, "/")),''));





        //Slider vertical lote


    function clickControl(el){
        var posScroll = $('.slider-thumnail').scrollTop();
        if($(el).hasClass('row-up')){
            $('.slider-thumnail').animate({
                scrollTop: posScroll - 76.40,
            },200);
            }else{

            $('.slider-thumnail').animate({
                scrollTop: posScroll + 66,
            },200);
            }
        }


 </script>




@include('includes.ficha.modals_ficha')

@endif
