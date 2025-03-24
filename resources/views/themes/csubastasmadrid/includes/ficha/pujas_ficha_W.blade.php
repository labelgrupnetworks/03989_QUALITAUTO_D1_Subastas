<div class="col-xs-12 info_single">
    <div class="info_single_title col-xs-12">
        <div class="sub-o">

            <p class="">{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}</p>

<span class="clock"><i class="fa fa-clock-o"></i><span data-countdown="{{ strtotime($lote_actual->start_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($lote_actual->start_session); ?>" data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span>
</span>
        </div>
        <div class="date_top_side_small">
            <span class="cierre_lote"></span>
          <?php /* no ponemos CET   <span id="cet_o"> {{ trans(\Config::get('app.theme').'-app.lot.cet') }}</span> */ ?>
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
    </div>
    <div class="col-xs-12 col-sm-6">
            <p class="cat">{{ trans(\Config::get('app.theme').'-app.lot.categories') }}</p>
            <p>
				@foreach($data['categories'] as $sec)
                   {{$sec->des_tsec}}
               	@endforeach
            </p>

    </div>
</div>
@if($lote_actual->fac_hces1!='D')
<div class="info_single col-xs-12 ficha-puja">
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
    @if ($lote_actual->tipo_sub == 'W' && $lote_actual->subabierta_sub == 'O' && $lote_actual->cerrado_asigl0 == 'N'  )
    <div class="col-lg-12">
        <div class="info_single_title">
            <div id="text_actual_max_bid" class=" <?=  $lote_actual->open_price >0? '':'hidden' ?>">
                {{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }} <strong><span id="actual_max_bid" >{{\Tools::moneyFormat($lote_actual->open_price) }}  </span> {{trans(\Config::get('app.theme').'-app.subastas.euros')}}</strong>

                @if (isset($data['js_item']['user']))
                    {{--<span class="winner {{(count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? '':'hidden' }}" >{{ trans(\Config::get('app.theme').'-app.subastas.exceeded') }}</span>--}}
                    {{--<span  class="no_winner  {{ (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? 'hidden':'' }}">{{ trans(\Config::get('app.theme').'-app.subastas.not_exceeded') }}</span>--}}
                @endif
            </div>
            <div  id="text_actual_no_bid" class=" <?=  $lote_actual->open_price >0? 'hidden':'' ?>"> {{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </div>
        </div>
    </div>
    @endif

        <div class="col-xs-12">
            <div class="info_single_content">
                @if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' && strtotime("now") > strtotime($lote_actual->start_session)  &&  strtotime("now")  < strtotime($lote_actual->end_session) )
                     <a href='{{  Routing::translateSeo('api/subasta').$data['subasta_info']->lote_actual->cod_sub."-".str_slug($data['subasta_info']->lote_actual->name)."-".$data['subasta_info']->lote_actual->id_auc_sessions }}'>
                            <button class="btn btn-lg btn-custom live-btn btn-color"><?=trans(\Config::get('app.theme').'-app.lot.bid_live')?></button>
                     </a>
                @endif
                @if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' &&  strtotime("now") > strtotime($lote_actual->orders_start)  &&   strtotime("now") < strtotime($lote_actual->orders_end))
                    <p><strong><?=trans(\Config::get('app.theme').'-app.lot.insert_max_puja_start')?></strong></p>
                    <div class="input-group group-pujar-custom">
                            <input id="bid_modal_pujar" placeholder="" class="form-control input-lg control-number" value="" type="text">
                            <div class="input-group-btn">
                                    <button id="pujar_ordenes_w" data-from="modal" type="button" class="btn btn-lg btn-custom btn-color" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}</button>
                            </div>
                    </div>
                @endif

            </div>
        </div>

</div>

@endif





<script>
   $(document).ready(function() {
        //calculamos la fecha de cierre
        $(".cierre_lote").html(format_date_large(new Date("{{$lote_actual->start_session}}".replace(/-/g, "/")),'{{ trans(\Config::get('app.theme').'-app.lot.from') }}'));
    });
</script>




