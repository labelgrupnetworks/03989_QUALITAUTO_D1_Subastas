<div>
    <div class="single-lot-desc-title desc">
        <h3>{{ trans($theme.'-app.lot.description') }}</h3>
        <p class="sub-o">{{ trans($theme.'-app.subastas.lot_subasta_presencial') }}</p>
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



<div class="info_single">
    <div class="info_single_title">
        <div class="sub-o hidden">
            <span class="clock timer">
                <span data-countdown="{{ strtotime($lote_actual->start_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($lote_actual->start_session); ?>" data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span>
            </span>
        </div>
    </div>
    <div class="exit-price prices">
        @if( \Config::get('app.estimacion'))
            <div class="price">
                <span class="pre">{{ trans($theme.'-app.subastas.estimate') }}</span>
                    <span class="pre">
                            {{$lote_actual->formatted_imptas_asigl0}} -  {{$lote_actual->formatted_imptash_asigl0}} {{ trans($theme.'-app.subastas.euros') }}
                    </span>
            </div>
        @elseif( \Config::get('app.impsalhces_asigl0') && $lote_actual->ocultarps_asigl0 != 'S')
            <div class="price">
                <span class="pre">{{ trans($theme.'-app.lot.lot-price') }}</span>
                <span class="pre">
                    {{$lote_actual->formatted_impsalhces_asigl0}} {{ trans($theme.'-app.subastas.euros') }}
                </span>
            </div>
        @endif
        <div class="divider-prices"></div>
        @if ($lote_actual->tipo_sub == 'W' && $lote_actual->subabierta_sub == 'O' && $lote_actual->cerrado_asigl0 == 'N'  )
        <div class="price">
            <span id="text_actual_max_bid" class="pre <?=  $lote_actual->open_price >0? '':'hidden' ?>">{{ trans($theme.'-app.lot.puja_actual') }}</span>
            <span id="actual_max_bid" class="pre <?= (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? 'winner':'no_winner' ?>">{{\Tools::moneyFormat($lote_actual->open_price) }}{{trans($theme.'-app.subastas.euros')}}</span>
            @if (isset($data['js_item']['user']))
                <small class="winner <?= (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? '':'hidden' ?>" ><?php /* trans($theme.'-app.subastas.exceeded') */ ?></small>
                <small  class="no_winner <?= (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? 'hidden':'' ?>"><?php /* trans($theme.'-app.subastas.not_exceeded') */ ?></small>
            @endif
        </div>
        <div class="info_single_title">

            <div  id="text_actual_no_bid" class=" <?=  $lote_actual->open_price >0? 'hidden':'' ?>"> {{ trans($theme.'-app.lot_list.no_bids') }} </div>
        </div>

        @endif



        <div class="date_top_side_small">
            <span class="cierre_lote"></span>
        </div>
    </div>
</div>

@if($lote_actual->fac_hces1!='D')
<div class="info_single  ficha-puja">

        <div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
        {{trans($theme.'-app.lot.max_puja')}}
            <strong><span id="tuorden">
            @if ( !empty($data['js_item']['user']['ordenMaxima']))
            {{ $data['js_item']['user']['ordenMaxima']}}
            @endif
            </span>
        {{trans($theme.'-app.subastas.euros')}}</strong>
        </div>

            <div class="info_single_content_button">
                @if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' &&  strtotime("now") > strtotime($lote_actual->orders_start)  &&   strtotime("now") < strtotime($lote_actual->orders_end))
                    <small><strong><?=trans($theme.'-app.lot.insert_max_puja_start')?></strong></small>
                    <div class="input-group direct-puja">
                            <input id="bid_modal_pujar" placeholder="{{ $data['precio_salida'] }}" class="form-control input-lg control-number" value="{{ $data['precio_salida'] }}" type="text">
                            <button id="pujar_ordenes_w" data-from="modal" type="button" class="btn-color" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans($theme.'-app.lot.place_bid') }}</button>
                    </div>
                @endif
                @if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' && strtotime("now") > strtotime($lote_actual->start_session)  &&  strtotime("now")  < strtotime($lote_actual->end_session) )
                <div class="">
                     <a href='{{  Routing::translateSeo('api/subasta').$data['subasta_info']->lote_actual->cod_sub."-".str_slug($data['subasta_info']->lote_actual->name)."-".$data['subasta_info']->lote_actual->id_auc_sessions }}'>
                            <span class="btn btn-custom live-btn mb-1"><?=trans($theme.'-app.lot.bid_live')?></span>
                     </a>
                    </div>
                @endif


            </div>


</div>

@endif





<script>
   $(document).ready(function() {
        //calculamos la fecha de cierre
        if($(window).width() < 768){
            $('.historial').hide()

        }

        $(".cierre_lote").html(format_date_large(new Date("{{$lote_actual->start_session}}".replace(/-/g, "/")),'{{ trans($theme.'-app.lot.from') }}'));
           letsClock()
    });
</script>




