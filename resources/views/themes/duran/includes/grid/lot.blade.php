
{{-- las etiquetas van a parta para simplificar el código --}}
<?php
	#lo pongo aquí para que funcione tambie en lotes relacionados y destacados.
	if($subasta_venta){
		$precio_salida = \Tools::moneyFormat($item->impsalhces_asigl0,"",2);
	}

?>
<div class="{{$class_square}}  square" {!! $codeScrollBack !!}>
    <a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?> >

		@include('includes.grid.labelLots')


        <div class="item_lot">
            <div class="item_img">
                <div data-loader="loaderDetacados" ></div>
                <img class="img-responsive"  src="{{$img}}" alt="{{$titulo}}">
            </div>

            <div class="data-container">
                    <div class="title_item">
                        <span class="ref">

							@if($subasta_web )
								{{ trans(\Config::get('app.theme').'-app.lot.lot-name') }}
							@else
								{{ trans(\Config::get('app.theme').'-app.lot.referencia') }}
							@endif
							@php
							$refLot  = $item->ref_asigl0;
								#si hay separador decimal ponemos los bises
								if(strpos($refLot ,'.')){
									if($item->tipo_sub=="W"){
										$refLot = str_replace(array(".1",".2",".3",".4",".5"), array(" A"," B", " C", " D", " E"),  $refLot );
									}else{
										#solo en las subastas presenciales pueden verse los bises, en el resto hay que quitarlo
										$refLot = str_replace(array(".1",".2",".3",".4",".5",".6",".7",".8",".9"), array("", "", "", "", "", "", "", "", "", ""),  $item->ref_asigl0);
										$refLot = substr($refLot ,-\config::get("app.substrRef"))+0;
									}

								}elseif( \config::get("app.substrRef")){
									#si no cogemos solo los últimos 7 numeros, ya que se usaran hasta 9, los dos primeros para diferenciar un lote cuando se ha vuelto a subir a subasta
									# le sumamos 0 para convertirlo en numero y así eliminamos los 0 a la izquierda

									$refLot = substr($refLot ,-\config::get("app.substrRef"))+0;
								}

							@endphp
							: {{ $refLot  }}
						</span>

					</div>
					<div class="description_lot">
						<?= $titulo?>
					</div>


				<div style="min-height:40px">
					@if($subasta_online && !$cerrado && !$retirado && !$devuelto)
						<p class="mt-15 salida-time background-principal text-center  d-flex align-items-center justify-content-centere" style="display: block;">
							<i class="fas fa-clock"></i>
							<span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
						</p>
					@elseif($subasta_web && !$cerrado && !$retirado && !$devuelto)
						<div class="date_lot">
							{{ trans(\Config::get('app.theme').'-app.lot_list.auction_date') }}<br>
						<span class="date-color">{{  date("d/m/Y H:i:s", strtotime($item->start)) }} </span>
						</div>
					@elseif($subasta_venta && !$cerrado && !$retirado && !$devuelto)
					<p class="salida-title mb-0 mt-2 col-xs-12 text-center" style="font-size:16px">
						<strong>	{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }} </strong>
					</p>

					@endif
				</div>
				<div class="data-price">
					@if( !$retirado && !$devuelto)
					<div class="row nopadding w-100">

						<div class="salida col-xs-12 " style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden'}}">
							@if($subasta_venta)

								<p class="precio-venta mb-0 col-xs-12 text-center">
									<strong> {{$precio_salida}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </strong>
								</p>
							@else
								<p class="salida-title mb-0 col-xs-7 text-left">
									{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}
								</p>
								<div class="salida-title mt-0 letter-price-salida col-xs-5 text-right">{{$precio_salida}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
							@endif

						</div>


						@if(($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)
							<div class="salida col-xs-12 ">
								<p class="mb-0 salida-title col-xs-7 text-left">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
								<div class="salida-title mt-0 letter-price-salida col-xs-5 text-right {{$winner}} ">{{ $maxPuja}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
							</div>
						@endif


						@if( $awarded)

								@if($cerrado && $remate &&  !empty($precio_venta)   )
									<div class="salida col-xs-12 text-center ">
										<p class="mb-0 salida-title col-xs-7 text-left">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</p>
										<div class="letter-price-salida salida-title mt-0 col-xs-5  text-right">{{ $precio_venta }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
									</div>

								@elseif($cerrado &&  empty($precio_venta) && !$compra)
									<div class="salida col-xs-12 text-center ">
										<p class="mb-0 salida-title col-xs-7 text-left">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
										<div class="letter-price-salida salida-title mt-0 col-xs-5 text-right"></div>
									</div>


								@endif
						@endif

					</div>
					@endif
				</div>



				@if (!$devuelto && !$retirado && !$sub_historica)
					@if($cerrado &&  empty($precio_venta) && $compra)
					<div class="pujar style-1">
						{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}
					</div>
					@elseif($subasta_venta  && !$cerrado )

					<div class="pujar style-1">
						{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}
					</div>
					@elseif(!$cerrado )
						@if($subasta_web )
							<div class="pujar style-2">
						@elseif($subasta_online )
							<div class="pujar style-5">
						@endif
						{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}
						</div>
					@endif

				@endif

            </div>

        </div>
    </a>
</div>
