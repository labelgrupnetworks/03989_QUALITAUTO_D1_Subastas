

<div class="d-flex ">
        <div class="sub-o col-xs-4 no-padding">
            <p class="info-type-auction">{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}</p>       
        </div>
        <div class="col-xs-8 ficha-info-clock">
            <span class="clock ficha-cinfo-clock">
                <i class="fas fa-clock"></i>
                <span data-countdownficha="{{strtotime($lote_actual->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($lote_actual->close_at,'large'); ?>" class="timer"></span>
            </div>          
    </div>

<div class="col-xs-12 ficha-desc-short no-padding">
<?php /*    <?= $lote_actual->descweb_hces1 ?> */ ?>
</div>

<div class="col-lg-8 col-md-12 no-padding ">
    <div class="col-xs-12 ficha-info-close-lot ">
        <div class="date_top_side_small">

            <span id="cierre_lote">{{ date("d-m-y H:i", strtotime($lote_actual->close_at)) }}</span>
        </div>
    </div> 

</div>

<div id="reload_inf_lot" class="col-lg-8 col-md-12 info-ficha-buy-info no-padding">
    <div class="col-lg-12">
        <div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
            {{trans(\Config::get('app.theme').'-app.lot.max_puja')}} 
            <strong>
                <span id="tuorden">
                    @if ( !empty($data['js_item']['user']['ordenMaxima'])) 
                        {{ $data['js_item']['user']['ordenMaxima']}}
                    @endif
                </span>
            {{trans(\Config::get('app.theme').'-app.subastas.euros')}}</strong>
        </div>
    </div>
    <div class=" col-xs-12 no-padding info-ficha-buy-info-price d-flex">
        @if( \Config::get('app.estimacion'))
            <div class="pre">
                <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.subastas.estimate') }}</p>
                <p class="pre-price">{{$lote_actual->formatted_imptas_asigl0}} -  {{$lote_actual->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                
            </div>        
        @elseif( \Config::get('app.impsalhces_asigl0'))
            <div class="pre">
                <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                <p class="pre-price">{{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </p>
               
                
            </div>
        @endif
        <div class="pre">
            <div id="text_actual_max_bid" class="pre-price price-title-principal <?=  count($lote_actual->pujas) >0? '':'hidden' ?>">
                <p class="pre-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                <strong>
                    <span id="actual_max_bid" >{{ $lote_actual->formatted_actual_bid }} €</span>
                    
                </strong>
            </div>
        </div>
    </div>

    <div class="col-xs-12 no-padding info-ficha-buy-info-price d-flex">            
        <div class="pre">
            <div  id="text_actual_no_bid" class="price-title-principal pre  <?=  count($lote_actual->pujas) >0? 'hidden':'' ?>"> {{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </div>
            @if (count($lote_actual->pujas) >0)
                <p class='explanation_bid t_insert pre-title' >{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}  </p>
                <strong><span class="siguiente_puja">{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </span></strong> 
                    @else
                    <p class='explanation_bid t_insert pre-title'>{{ trans(\Config::get('app.theme').'-app.lot.min_puja') }}  </p>
                    <strong><span class="siguiente_puja">{{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </span></strong>
                @endif 
        </div>
        </div>
        <div class="insert-bid-input">
            <?php /*
            <small class="min">
                {{ trans(\Config::get('app.theme').'-app.subastas.price_minim') }}
            <span class="min precio_minimo_alcanzado hidden">
                    {{ trans(\Config::get('app.theme').'-app.subastas.reached') }}
            </span>
        
                <span class="min precio_minimo_no_alcanzado hidden">
                    {{ trans(\Config::get('app.theme').'-app.subastas.no_reached') }}
                </span>
            </small>
            **/ 
            ?>
            @if (Session::has('user') &&  Session::get('user.admin'))
                <input id="ges_cod_licit" name="ges_cod_licit" class="form-control" type="text" value="" type="text" style="border: 1px solid red;" placeholder="Código de licitador">
                @if ($lote_actual->subabierta_sub == 'P')
                    <input type="hidden" id="tipo_puja_gestor" value="abiertaP" >
                @endif
            @endif
            <div class="insert-bid insert-max-bid">{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}</div>
            <div class="input-group group-pujar-custom d-flex">
                                <input id="bid_amount" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" type="text" value="{{ $data['precio_salida'] }}">
                <div class="input-group-btn">
                    <button type="button" data-from="modal" class="lot-action_pujar_on_line ficha-btn-bid button-principal <?= Session::has('user')?'add_favs':''; ?>" type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" >{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
                </div>
                
            </div>
        </div>
    </div> 
    <div class="col-xs-12 no-padding ficha-tipo-v">

            <div class="col-xs-12 no-padding desc-lot-title d-flex justify-content-space-between">
                    <p class="desc-lot-profile-title">{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>

            </div>
            <div class="col-xs-12 no-padding desc-lot-profile-content">
                    <p><?= $lote_actual->desc_hces1 ?></p>
            </div>
                
        </div>         
<script>
    
    
    
    
    
    $(document).ready(function() {   
        
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
                           $(".timer").data('countdownficha',data.countdown); 
                            

                        }


                    }
                });
            } 
        });
    });
</script>
</div>
        

