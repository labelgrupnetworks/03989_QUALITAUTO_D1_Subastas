
<div id="reload_inf_lot" class="col-xs-12 info-ficha-buy-info no-padding">

    @if ($lote_actual->ocultarps_asigl0 != 'S')
        <div class="col-xs-6 col-md-12 no-padding info-ficha-buy-info-price mt-1">
                <div class="pre d-flex justify-content-space-between align-items-center precio-puja-container">
                    <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                    <p class="pre-price">{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </p>

                </div>
        </div>
    @endif

    <div class=" col-xs-6 col-md-12 no-padding info-ficha-buy-info-price mt-1">

            <div id="text_actual_max_bid" class="pre-price price-title-principal <?=  count($lote_actual->pujas) >0? '':'hidden' ?>">
                <div class="pre justify-content-space-between d-flex align-items-center precio-puja-container">
                    <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                    <strong>
						{{-- aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color --}}
						@php
							if(Session::has('user')){
								$class = (!empty($lote_actual->max_puja) &&   $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit']) ? 'mine':'other';
							}
							else{
								$class = '';
							}
						@endphp
                        <span id="actual_max_bid" class="pre-price {{$class}}">{{ $lote_actual->formatted_actual_bid }} €</span>

                    </strong>
                 </div>

                    @if (isset($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 > 0 && Session::has('user'))
                        <div class="pre flex-column justify-content-space-between d-flex">
                            <div class="pre_min">

                                    <p class='pre-title'> {{ trans(\Config::get('app.theme').'-app.subastas.price_minim') }}: </p>
                                    <strong>
                                    <span class="precio_minimo_alcanzado mine hidden">{{ trans(\Config::get('app.theme').'-app.subastas.reached') }}</span>
                                    <span class="precio_minimo_no_alcanzado other hidden">{{ trans(\Config::get('app.theme').'-app.subastas.no_reached') }}</span>
                                    </strong>

                            </div>
                        </div>
                    @endif
                </div>
            </div>

	</div>


	<div class="info_single col-xs-12 ficha-puja no-padding">
		<div class="col-lg-12 no-padding">
			<div class="info_single_title mt-2 hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
			{{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
				<strong><span id="tuorden">
					@if ( !empty($data['js_item']['user']['ordenMaxima']))
					{{ $data['js_item']['user']['ordenMaxima']}}
					@endif
					</span>
					{{trans(\Config::get('app.theme').'-app.subastas.euros')}}
				</strong>
				<?php
					#lo quito por que han pedido que esté en el panel de usuario
					//<input style="float: right;margin-top: -10px;" class="btn btn-danger delete_order" type="button" ref="{{$data['subasta_info']->lote_actual->ref_asigl0}}" sub="{{$data['subasta_info']->lote_actual->cod_sub}}" value="{{ trans(\Config::get('app.theme').'-app.user_panel.delete_orden') }}">
				?>
			</div>
		</div>
	</div>

        <div class="insert-bid-input col-lg-12 d-flex justify-content-center flex-column no-padding">

            @if (Session::has('user') &&  Session::get('user.admin')  )
				<div class="d-block w-100">
					<input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text" style="border: 1px solid red;" placeholder="Código de licitador">
					@if ($subasta_abierta_P)
						<input type="hidden" id="tipo_puja_gestor" value="abiertaP" >
					@endif
				</div>
            @endif

			@if ($start_session)
				<div class="input-group d-block group-pujar-custom mr-1">
					<div>
						<div class="insert-bid insert-max-bid mb-1">{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }} 	<a href="javascript:;" data-toggle="modal" data-target="#modalAjax" class="info-ficha-lot pt-1 c_bordered" data-ref="{{ Routing::translateSeo('pagina')."info-pujas-online"  }}?modal=1" data-title="{{ trans(\Config::get('app.theme').'-app.lot.title_info_pujas') }}"><i class="fas fa-info-circle"></i></a>
						</div>
					</div>
					<div class="input-group-btn boton-pujas mt-1 boton-pujar-online mb-2">
						<button type="button" data-from="modal" value="{{$data['precio_salida']}}" class="lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal js-lot-action_pujar_escalado <?= Session::has('user')?'add_favs':''; ?>" type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" >{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}
							<span id="boton_puja_directa_JS">{{$data['precio_salida']}} </span>€

							</button>
					</div>

					<div class="col-xs-7 no-padding">
						<input id="bid_amount" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number mb-2" type="text" value="{{ $data['precio_salida'] }}">
					</div>
					<div class="col-xs-5 no-padding text-right">
						<button type="button" data-from="modal" class="lot-action_pujar_on_line  ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>" type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" >{{ trans(\Config::get('app.theme').'-app.lot.autopuja') }}</button>
					</div>

				</div>
			@endif
        </div>



<?php //solo se debe recargar la fecha en las subatsas tipo Online, ne las abiertas tipo P no se debe ejecutar ?>
@if($subasta_online)
    <script>
        $(document).ready(function() {

            $("#actual_max_bid").bind('DOMNodeInserted', function(event) {
                if (event.type == 'DOMNodeInserted') {

                   $.ajax({
                        type: "GET",
                        url:  "/lot/getfechafin",
                        data: { cod: cod_sub, ref: ref},
                        success: function( data ) {

                            if (data.status == 'success'){
                               $(".timer").data('ini', new Date().getTime());
                               $(".timer").data('countdownficha',data.countdown);
                               //var close_date = new Date(data.close_at * 1000);
                              // $("#cierre_lote").html(close_date.toLocaleDateString('es-ES') + " " + close_date.toLocaleTimeString('es-ES'));
                               $("#cierre_lote").html(format_date_large(new Date(data.close_at * 1000),''));
                            }


                        }
                    });
                }
            });
        });
    </script>
@endif
</div>


