<div class="d-flex justify-content-space-between">
    <div class="sub-o col-xs-12 no-padding">
        <p class="info-type-auction">{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}</p>       
    </div>
    <div class="col-xs-12 ficha-info-clock text-right">
        <span class="clock ficha-cinfo-clock"><i class="fas fa-clock"></i><span data-countdown="{{ strtotime($lote_actual->start_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($lote_actual->start_session); ?>" data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span>
    </div>          
</div>
<?php 
    //lo comento por que ya aparece la descweb en el titulo
    /*                        
    <div class="col-xs-12 ficha-desc-short">
        <?= $lote_actual->descweb_hces1 ?>
    </div>
    */
?>
<div class="col-md-12 col-xs-12 no-padding">
    <div class="col-xs-12 ficha-info-close-lot">
        <div class="date_top_side_small">            
            <span class="cierre_lote"></span>          
            <?php /* no ponemos CET   <span id="cet_o"> {{ trans(\Config::get('app.theme').'-app.lot.cet') }}</span> */ ?>
        </div>
    </div>   
    <div class="col-xs-12 no-padding ficha-tipo-v">
        <div class="col-xs-12 no-padding desc-lot-title d-flex justify-content-space-between">
            <p class="desc-lot-profile-title">{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>
        </div>
        <div class="desc-lot-profile-content-container col-xs-12 no-padding">
            <div class="desc-lot-profile-content">
                <p><?= $lote_actual->desc_hces1 ?></p>
            </div>     

        </div>
    </div>
    @if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' && strtotime("now") > strtotime($lote_actual->start_session)  &&  strtotime("now")  < strtotime($lote_actual->end_session) )
        <div class="col-xs-12 no-padding">
            <div class="ficha-live-btn-content">     
                <a class="ficha-live-btn-link secondary-button" href='{{\Tools::url_real_time_auction($data['subasta_info']->lote_actual->cod_sub,$data['subasta_info']->lote_actual->name,$data['subasta_info']->lote_actual->id_auc_sessions)}}'>
                    <div class="bid-online"></div>
                    <div class="bid-online animationPulseRed"></div>
                    <?=trans(\Config::get('app.theme').'-app.lot.bid_live')?>    
                </a>
            </div>
        </div>
    @endif    
    <div class="col-xs-12 info-ficha-buy-info no-padding">
        <div class="info-ficha-buy-info-price d-flex">
                @if( \Config::get('app.estimacion'))
                <div class="pre">
                    <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.subastas.estimate') }}</p>
                    <p class="pre-price">{{$lote_actual->formatted_imptas_asigl0}} -  {{$lote_actual->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                </div>
                
                @elseif( \Config::get('app.impsalhces_asigl0'))
                <div class="pre">
                    <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                    <p class="pre-price">{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                </div>
                @endif

                @if ($lote_actual->tipo_sub == 'W' && $lote_actual->subabierta_sub == 'S' && $lote_actual->cerrado_asigl0 == 'N'  )                           
                    <div id="text_actual_max_bid" class="price-title-principal pre <?=  $lote_actual->open_price >0? '':'hidden' ?>">
                            <p class="pre-title-principal">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                            <div class="pre-price"><span id="actual_max_bid" >{{\Tools::moneyFormat($lote_actual->open_price) }} </span> {{trans(\Config::get('app.theme').'-app.subastas.euros')}}</div>
                        @if (isset($data['js_item']['user']))
                            <span class="winner <?= (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? '':'hidden' ?>" >{{ trans(\Config::get('app.theme').'-app.subastas.exceeded') }}</span> 
                            <span  class="no_winner <?= (count($data['ordenes']) > 0 && head($data['ordenes'])->cod_licit == $data['js_item']['user']['cod_licit'])? 'hidden':'' ?>">{{ trans(\Config::get('app.theme').'-app.subastas.not_exceeded') }}</span>
                        @endif
                    </div>
                     
                    <div  id="text_actual_no_bid" class="pre <?=  $lote_actual->open_price >0? 'hidden':'' ?>"> <p class="pre-title-principal">{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}</p></div>
                @endif
            </div>
        </div>

        @if($lote_actual->fac_hces1!='D')
        <div class="info_single col-xs-12 ficha-puja no-padding">
            <div class="col-lg-12 no-padding">
                <div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
                {{trans(\Config::get('app.theme').'-app.lot.max_puja')}}
                    <span id="tuorden">
                    @if ( !empty($data['js_item']['user']['ordenMaxima'])) 
                        @if (isset($data['ordenes']) && isset($data['ordenes'][0]) && $data['ordenes'][0]->cod_licit == $data['js_item']['user']['cod_licit'])
                            <b class="winner">
                        @else
                            <b class="no_winner">
                        @endif
                            
                        {{ $data['js_item']['user']['ordenMaxima']}}
                        {{trans(\Config::get('app.theme').'-app.subastas.euros')}}
                    </b>

                    @endif
                    </span>
                </div>
            </div>     
        </div>
    @endif
        <div class="ficha-info-item-for-pay  col-xs-12 no-padding">
            <div class="info_single_content">
                @if( $lote_actual->cerrado_asigl0=='N' && $lote_actual->fac_hces1=='N' &&  strtotime("now") > strtotime($lote_actual->orders_start)  &&   strtotime("now") < strtotime($lote_actual->orders_end))   
                    <div class="insert-max-bid"><?=trans(\Config::get('app.theme').'-app.lot.insert_max_puja_start')?></div>
                    <div class="input-group group-pujar-custom d-flex">
                            <input id="bid_modal_pujar" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number col-xs-8" value="{{ $data['precio_salida'] }}" type="text">
                            <div class="input-group-btn">
                                <button id="pujar_ordenes_w" data-from="modal" type="button" class="ficha-btn-bid button-principal col-xs-4" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}</button>
                            </div>
                    </div>
                @endif
                
            </div>
    
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
        
        
       
</div>





    
<script>
   $(document).ready(function() {
        //calculamos la fecha de cierre
        $(".cierre_lote").html(format_date_large(new Date("{{$lote_actual->start_session}}".replace(/-/g, "/")),'{{ trans(\Config::get('app.theme').'-app.lot.from') }}'));
    });   
</script>  
        


				
