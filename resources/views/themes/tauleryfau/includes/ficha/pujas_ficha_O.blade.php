<div class="description-info-puja-wrapper d-flex flex-column">
<div>
    <div class="single-lot-desc-title desc">
        <h3>{{ trans($theme.'-app.lot.description') }}</h3>
            <div class='time-shared-wrapper' style='margin-top: 0px;'>
                <span class="clock timer">
                    @if($lote_actual->tipo_sub == 'W' )
                    <span data-countdown="{{ strtotime($lote_actual->start_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($lote_actual->start_session); ?>" data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span>
                    @else
                    <span data-countdown="{{strtotime($lote_actual->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($lote_actual->close_at,'large'); ?>" class="timer"></span>
                    @endif
                </span>
            </div>
    </div>
    <div>
        <div class="single-lot-desc-content" id="box">
            @if( \Config::get('app.descweb_hces1'))
                <?= $lote_actual->descweb_hces1 ?>
            @elseif ( \Config::get('app.desc_hces1' ))
                <?= $lote_actual->desc_hces1 ?>
            @endif
        </div>
    </div>
</div>
<div class="info-puja-wrapper">
<div class="info_single">
    <div class="info_single_title">
        <div class="sub-o hidden">
        </div>
    </div>
    <div class="exit-price prices origenactualizable">
        @if( \Config::get('app.estimacion'))
            <div class="price">
                <span class="pre title">{{ trans($theme.'-app.subastas.estimate') }}</span>
                <div>
                    <span class="pre">
                        {{$lote_actual->formatted_imptas_asigl0}} - {{$lote_actual->formatted_imptash_asigl0}} {{ trans($theme.'-app.subastas.euros') }}
                    </span>
                    <div class="vertical-bar " >|</div>
                    <span id="impsalexchange" class="currency-trnas" style="display: contents"></span>
                </div>
            </div>
        @elseif( \Config::get('app.impsalhces_asigl0') && $lote_actual->ocultarps_asigl0 != 'S')
            <div class="price">
                <span class="pre title">{{ trans($theme.'-app.lot.lot-price') }}</span>
                <div>
                    <span>{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans($theme.'-app.subastas.euros') }}  </span>
                    <div class="vertical-bar" >|</div>
                    <span id="impsalexchange" class="currency-trnas" style="display: contents"></span>
                </div>
            </div>
        @endif
        <div class="divider-prices"></div>
        <div class="price">
            <?php
            $you_bid = false;
            foreach($lote_actual->pujas as $bid){

                if(!empty($data['js_item']['user']) && $bid->cod_licit == $data['js_item']['user']['cod_licit']){
                    $you_bid = true;
                }

            }
            ?>
            @if (count($lote_actual->pujas) > 0 && Session::has('user') && $you_bid == true)
                <span id="text_actual_max_bid" class="pre title">{{ trans($theme.'-app.lot.puja_actual') }}</span>
                <div>
                    <span id="actual_max_bid" class="<?=  ($lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])? 'mine':'other' ?>">{{ $lote_actual->formatted_actual_bid }} €</span>
                    <div class="vertical-bar <?= (count($lote_actual->pujas) == 0)? 'hidden':'' ?>" >|</div>
                    <span id="impsalexchange-actual" class="currency-trnas"></span>
                </div>
            @elseif(count($lote_actual->pujas) == 0)

                <span  id="text_actual_no_bid"> {{ trans($theme.'-app.lot_list.no_bids') }} </span>
                <span id="text_actual_max_bid" class="hidden">{{ trans($theme.'-app.lot.puja_actual') }}</span>
                <div>
                    <span id="actual_max_bid" class="hidden"></span>
                    <div class="vertical-bar  " >|</div>
                    <span id="impsalexchange-actual" class="currency-trnas hidden"></span>
                </div>
            @elseif($lote_actual->pujas > 0 && $you_bid == false)
                <span id="text_actual_max_bid" class="pre title">{{ trans($theme.'-app.lot.puja_actual') }}</span>
                <div>
                    <span id="actual_max_bid" class="gold">{{ $lote_actual->formatted_actual_bid }} €</span>
                    <div class="vertical-bar <?= (count($lote_actual->pujas) == 0)? 'hidden':'' ?>" >|</div>
                    <span id="impsalexchange-actual" class="currency-trnas"></span>
                </div>
            @endif
            <?php /*
                <span class="min">{{ trans($theme.'-app.subastas.price_minim') }}</span>
                <div class="min precio_minimo_alcanzado hidden">
                    {{ trans($theme.'-app.subastas.reached') }}
                </div>
                <div class="min precio_minimo_no_alcanzado hidden">
                    {{ trans($theme.'-app.subastas.no_reached') }}
            </div>
            */?>
        </div>
    </div>
</div>
<div class="info_single ficha-puja-o" style="margin-top: 5px;">
    <?php
        if(empty($data['js_item']['user']['ordenMaxima']) && !empty($data['js_item']['user']['pujaMaxima']) ) {
            $data['js_item']['user']['ordenMaxima'] = $data['js_item']['user']['pujaMaxima']->formatted_imp_asigl1;
        }


    ?>

    <div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
        {{trans($theme.'-app.lot.max_puja')}}
        <strong>
            <span id="tuorden">
				@php
					if (!empty($data['js_item']['user']['ordenMaxima']) && !empty($data['js_item']['user']['pujaMaxima']) ) {
						$ordenMax = $data['js_item']['user']['ordenMaxima'];
						$pujaMax = $data['js_item']['user']['pujaMaxima']->formatted_imp_asigl1;
						if ($ordenMax > $pujaMax) {
							echo $ordenMax;
						} else {
							echo $pujaMax;
						}
					} elseif (!empty($data['js_item']['user']['ordenMaxima'])) {
						echo $data['js_item']['user']['ordenMaxima'];
					} else {
						echo '';
					}
				@endphp
            </span>
        </strong>
        {{trans($theme.'-app.subastas.euros')}}</strong>
    </div>


        @if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' && strtotime("now") > strtotime($lote_actual->start_session)  &&  strtotime("now")  < strtotime($lote_actual->end_session) )
            <div class="info_single_content_button">
                <div class="">
                    <a target="_blank" href='{{  Routing::translateSeo('api/subasta').$data['subasta_info']->lote_actual->cod_sub."-".str_slug($data['subasta_info']->lote_actual->name)."-".$data['subasta_info']->lote_actual->id_auc_sessions }}'>
                        <span class="btn btn-custom live-btn mb-1"><?=trans($theme.'-app.lot.bid_live')?></span>
                    </a>
                </div>
            </div>
        @endif
        @if($lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N')
            <div class="info_single_content">
                <?php
                //@if (count($lote_actual->pujas) >0)
                   // <div class='explanation_bid t_insert text-right' style="font-size:11px;">{{ trans($theme.'-app.lot.next_min_bid') }}  <strong><span class="siguiente_puja"> </span></strong> {{ trans($theme.'-app.subastas.euros') }} </div>
                //@else
                  //  <div class='explanation_bid t_insert text-right' style="font-size:11px;">{{ trans($theme.'-app.lot.min_puja') }}  <strong><span class="siguiente_puja"> </span></strong> {{ trans($theme.'-app.subastas.euros') }} </div>
                //@endif?>

                <div class="input-group direct-puja">

						<input id="bid_amount"  class="form-control" type="hidden" value="{{ $data['precio_salida'] }}" type="text" placeholder="{{ trans($theme.'-app.lot.insert_max_puja') }}">
						<div class="d-flex align-items-center">

								<div class="currency-input currency-simbol w-100">
									<input id="bid_amount_libre" maxlength="6"  class="form-control control-number input-currency" type="number" placeholder="{{ trans($theme.'-app.lot.insert_max_puja') }}">
								</div>

							<div class="w-100">
								<button type="button" data-from="modal" class="btn-color lot-action_pujar_on_line <?= Session::has('user')?'add_favs':''; ?>">{{ trans($theme.'-app.lot.pujar') }}</button>
							</div>
					</div>
					<!-- TODO -->

					<div class="escalados-container d-flex justify-content-space-between">
					@foreach ($lote_actual->siguientes_escalados as $escalado)
					<button type="button" data-from="modal" data-escalado-position="{{$loop->index}}" value="{{$escalado}}" class="btn-color lot-action_pujar_on_line js-lot-action_pujar_escalado <?= Session::has('user')?'add_favs':''; ?>">
							{{ trans($theme.'-app.sheet_tr.place_bid') }}
							<span value="{{$escalado}}" id="button-escalado">{{ \Tools::moneyFormat($escalado) }}</span>
							{{trans($theme.'-app.subastas.euros')}}
						</button>
					@endforeach
					</div>
                        @if (Session::has('user') &&  Session::get('user.admin'))
                            <input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text" style="border: 1px solid red;" placeholder="Código de licitador">
                            @if ($lote_actual->subabierta_sub == 'P')
                                <input type="hidden" id="tipo_puja_gestor" value="abiertaP" >
                            @endif
                        @endif

                </div>
            </div>
        @endif

</div>
</div>
</div>
<script>
    $(document).ready(function() {

        <?php //cargamos el valor actual del preci ode salida ?>



         $('.open-popup-inf').magnificPopup({

            items: [
               {
                 src: $('.open-popup-inf').attr("url"),
                 type: 'iframe' // this overrides default type
               },
               {
                 src: '#modal_frame', // CSS selector of an element on page that should be used as a popup
                 type: 'inline'
               }
             ],

         });
        //Hacemos un unbind para quitar el evento autocomplete no lo quieres, no se puede quitar el id porque se utiliza para cojer la puja
        $( "#bid_amount" ).unbind();
        @if($lote_actual->tipo_sub == 'O' || $lote_actual->tipo_sub == 'P')
            //calculamos la fecha de cierre
            //$("#cierre_lote").html(format_date(new Date("{{$lote_actual->close_at}}".replace(/-/g, "/"))));
            $("#actual_max_bid").bind('DOMNodeInserted', function(event) {
                if (event.type == 'DOMNodeInserted') {

                   $.ajax({
                        type: "GET",
                        url:  "/lot/getfechafin",
                        data: { cod: cod_sub, ref: ref},
                        success: function( data ) {

                            if (data.status == 'success'){
                                  $(".timer").data('ini', new Date().getTime());
                                  $(".timer").data('countdown',data.countdown);
                            }


                        }
                    });
                }
            });
        @endif


        letsClock()
    });




</script>



