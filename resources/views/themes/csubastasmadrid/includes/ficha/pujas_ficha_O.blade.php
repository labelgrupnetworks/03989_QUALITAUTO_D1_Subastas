
<div class="col-xs-12 info_single">

        <div class="info_single_title col-xs-12">
            <div class="sub-o">
                <p class="">
					@if($lote_actual->tipo_sub == 'O')
						{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}
					@else
						{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}
					@endif
				</p>
				<span class="clock "><i class="fa fa-clock-o"></i>
					@if($lote_actual->tipo_sub == 'O')
                     	<span data-countdown="{{strtotime($lote_actual->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($lote_actual->close_at,'large'); ?>" class="timer"></span>
					@else
					<span data-countdown="{{ strtotime($lote_actual->start_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($lote_actual->start_session); ?>" data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span>
				 	@endif
				</span>
            </div>
            <div class="date_top_side_small">
                <span class="cierre_lote">{{ $lote_actual->close_at }}</span>
            </div>
        </div>

    <div class="col-xs-10 col-sm-6 exit-price">
                        @if( \Config::get('app.estimacion'))
                            <p class="pre">{{ trans(\Config::get('app.theme').'-app.subastas.estimate') }}</p>
                            <div class="pre">
                                    {{$lote_actual->formatted_imptas_asigl0}} -  {{$lote_actual->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                            </div>
                        @elseif( \Config::get('app.impsalhces_asigl0') && $lote_actual->ocultarps_asigl0 != 'S')
                            <p class="pre">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                            <div class="pre">
								@if($lote_actual->impsalhces_asigl0 ==0)
								{{ trans(\Config::get('app.theme').'-app.lot.free') }}
								@else
                                    {{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
								@endif
							</div>
                        @endif
                        <div class="hidden">
                        <p class="min">{{ trans(\Config::get('app.theme').'-app.subastas.price_minim') }}</p>
                        <div class="min precio_minimo_alcanzado hidden">
                               {{ trans(\Config::get('app.theme').'-app.subastas.reached') }}
                        </div>
                         <div class="min precio_minimo_no_alcanzado hidden">
                               {{ trans(\Config::get('app.theme').'-app.subastas.no_reached') }}
                        </div>
                        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <p class="cat">{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</p>
        <?php
           $category = new \App\Models\Category();
           $tipo_sec = $category->getSecciones($data['js_item']['lote_actual']->sec_hces1);
        ?>
        <p>
           @foreach($tipo_sec as $sec)
               {{$sec->des_tsec}}
           @endforeach
        </p>

    </div>
</div>

<div class="info_single col-xs-12 ficha-puja-o">
    <div class="col-lg-12">
        <div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
        {{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
                 <strong><span id="tuorden">
                 @if ( !empty($data['js_item']['user']['ordenMaxima']))
                 {{ $data['js_item']['user']['ordenMaxima']}}
                 @endif
                 </span>
        {{trans(\Config::get('app.theme').'-app.subastas.euros')}}</strong>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="info_single_content">
            <?php
            $you_bid = false;
            foreach($lote_actual->pujas as $bid){

                if(!empty($data['js_item']['user']) && $bid->cod_licit == $data['js_item']['user']['cod_licit']){
                    $you_bid = true;
                }

            }
            ?>
            @if (count($lote_actual->pujas) > 0 && Session::has('user') && $you_bid == true)
                <span id="text_actual_max_bid" class=" ">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
                <span id="actual_max_bid" class="<?=  ($lote_actual->max_puja->cod_licit == $data['js_item']['user']['cod_licit'])? 'mine':'other' ?>">{{ $lote_actual->formatted_actual_bid }} €</span>
            @elseif(count($lote_actual->pujas) == 0)
                <span  id="text_actual_no_bid"> {{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </span>
                 <span id="text_actual_max_bid" class="hidden">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
                <span id="actual_max_bid" class="hidden"></span>
            @elseif($lote_actual->pujas > 0 && $you_bid == false)
                <span id="text_actual_max_bid" class=" ">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
                <span id="actual_max_bid" >{{ $lote_actual->formatted_actual_bid }} €</span>
            @endif
            <br>
            @if (count($lote_actual->pujas) >0)
                <small class='explanation_bid t_insert' style="font-size:13px; font-weight: 900">{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}  <strong><span class="siguiente_puja">{{ $data['precio_salida'] }}</span></strong> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </small>
            @else
                <small class='explanation_bid t_insert' style="font-size:13px; font-weight: 900">{{ trans(\Config::get('app.theme').'-app.lot.min_puja') }}  <strong><span class="siguiente_puja">{{ $data['precio_salida'] }}</span></strong> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </small>
            @endif

            <div class="insert_bid">
                <p><strong>{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}</strong></p>
            </div>
                <div class="input-group">
                        <input id="bid_amount" placeholder="{{ $data['precio_salida'] }}" class="form-control input-lg control-number" type="text" value="{{ $data['precio_salida'] }}">
                        <div class="input-group-btn" style="vertical-align: top;">
                                <button type="button" data-from="modal" class="lot-action_pujar_on_line btn btn-lg btn-custom <?= Session::has('user')?'add_favs':''; ?>" type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" >{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
                        </div>
                        <br>

                </div>

                 <div class="input-group">
                  <br>
                    @if (Session::has('user') &&  Session::get('user.admin'))
                        <input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text" style="border: 1px solid red;" placeholder="Código de licitador">
                        @if ($lote_actual->subabierta_sub == 'P')
                            <input type="hidden" id="tipo_puja_gestor" value="abiertaP" >
                        @endif
                    @endif
                </div>

        </div>
    </div>
</div>

<script>






    $(document).ready(function() {

        //calculamos la fecha de cierre
		//$("#cierre_lote").html(format_date(new Date("{{$lote_actual->close_at}}".replace(/-/g, "/"))));


		@if($lote_actual->tipo_sub == 'W')
			$(".cierre_lote").html(format_date_large(new Date("{{$lote_actual->start_session}}".replace(/-/g, "/")),'{{ trans(\Config::get('app.theme').'-app.lot.from') }}'));
		@else
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
    });
</script>



