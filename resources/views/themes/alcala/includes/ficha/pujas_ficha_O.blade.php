
<div id="reload_inf_lot" class="col-xs-12 info-ficha-buy-info no-padding">
    <div class="col-xs-12">
        <div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
            {{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
            <strong>
                <span id="tuorden">
                    @if ( !empty($data['js_item']['user']['ordenMaxima']))
                        @if ( !empty($lote_actual->max_puja) &&   $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])
                            {{ $lote_actual->formatted_actual_bid }}
                        @else
                            {{ $data['js_item']['user']['ordenMaxima']}}
                        @endif
                    @endif
                </span>
            {{trans(\Config::get('app.theme').'-app.subastas.euros')}}</strong>
        </div>
    </div>
    <div class=" col-xs-12 no-padding info-ficha-buy-info-price d-flex">

			@if ($lote_actual->ocultarps_asigl0 != 'S')
				<div class="pre">
					<p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
					<p class="pre-price">{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </p>
		
				</div>
			@endif
            <div class="pre">
                <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}</p>
                <p class="pre-price">{{ \Tools::moneyFormat($lote_actual->imptas_asigl0)}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </p>

            </div>

    </div>
    <div class=" col-xs-12 no-padding info-ficha-buy-info-price">
        <div class="pre">
            <div id="text_actual_max_bid" class="pre-price price-title-principal <?=  count($lote_actual->pujas) >0? '':'hidden' ?>">
                <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                <strong>
                    {{-- aparecera en rojo(clase other) si no eres el ganador y en verde si loeres (clase mine) , si no estas logeado no se modifica el color --}}
                    @if(Session::has('user'))
                        @php($class = (!empty($lote_actual->max_puja) &&   $lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])? 'mine':'other')
                    @else
                        @php($class = '')
                    @endif
                    <span id="actual_max_bid" class="{{$class}}">{{ $lote_actual->formatted_actual_bid }} €</span>

                </strong>
            </div>
        </div>
    </div>
    <div class="col-xs-12 no-padding info-ficha-buy-info-price border-top-bottom">
        <div class="pre d-flex mt-2 mb-2 ">
            <div  id="text_actual_no_bid" class="price-title-principal pre col-xs-12 col-sm-6 no-padding <?=  count($lote_actual->pujas) >0? 'hidden':'' ?>"> {{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </div>
            <div class="col-xs-12 col-sm-6 no-padding">
            @if ($hay_pujas)
                    <p class='explanation_bid t_insert pre-title' >{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}  </p>
                    <strong><span class="siguiente_puja">{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </span></strong>
                @else
                    <p class='explanation_bid t_insert pre-title'>{{ trans(\Config::get('app.theme').'-app.lot.min_puja') }}  </p>
                    <strong><span class="siguiente_puja">{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </span></strong>
                @endif
            </div>
        </div>
    </div>
        <div class="insert-bid-input col-lg-10 col-lg-offset-1 d-flex justify-content-center flex-column">

            @if (Session::has('user') &&  Session::get('user.admin'))
            <div class="d-block w-100">
                <input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text" style="border: 1px solid red;" placeholder="Código de licitador">
                @if ($subasta_abierta_P)
                    <input type="hidden" id="tipo_puja_gestor" value="abiertaP" >
                @endif
            </div>
                @endif

            @if ($supera_riesgo)
            <div class="input-group d-block group-pujar-custom ">
                <div>
                    <div class="insert-bid insert-max-bid mb-1">{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}</div>
                </div>
                <div class="d-flex mb-2">
                    <input id="bid_amount" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" type="text" value="{{ $data['precio_salida'] }}">
                <div class="input-group-btn">
                    <button type="button" data-from="modal" class="lot-action_pujar_on_line ficha-btn-bid ficha-btn-bid-height button-principal <?= Session::has('user')?'add_favs':''; ?>" type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" >{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
                </div>
            </div>
            @endif

            </div>
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


