<?php

    //si se ha superado el precio minimo
    $min_price_surpass = (count($lote_actual->pujas) >0 && $lote_actual->actual_bid >=  $lote_actual->impres_asigl0  );
                
?>


<div class="col-xs-12 info_single">
    <div class="info_single_title col-xs-12">
        <div class="sub-o">
            <p class="">{{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}</p>
            <div class="date_top_side_small">
                <span id="cierre_lote">{{ $lote_actual->close_at }}</span>
            </div>
        </div>
        <div class="col-xs-12 no-padding col-sm-6 clock-ficha">
            <span class="clock "><i class="fa fa-2x fa-clock-o"></i>
                <span data-countdown="{{strtotime($lote_actual->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($lote_actual->close_at,'large'); ?>" class="timer"></span>
            </span>
        </div>
        
    </div>
    @if( \Config::get('app.estimacion'))
        <div class="col-xs-12 col-lg-6 col-md-12 col-sm-6 exit-price">
            <p class="pre text">{{ trans(\Config::get('app.theme').'-app.subastas.estimate') }}</p>
                <div class="pre">
                        {{$lote_actual->formatted_imptas_asigl0}} -  {{$lote_actual->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                </div>
        </div>
    @endif
    @if( \Config::get('app.impsalhces_asigl0'))
        <div class="col-xs-12 col-lg-6 col-md-12 col-sm-6  exit-price">
            <p class="pre text">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                <div class="pre ">
                    {{$lote_actual->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                </div>
        </div>
    @endif
    
    <div class="col-xs-12">
        <div class="col-xs-12 col-sm-6 categories no-padding">
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
        <div class="col-xs-12 col-sm-6 no-padding text-right">
            <p class="shared">{{ trans(\Config::get('app.theme').'-app.lot.share_lot') }}</p>
            @include('includes.ficha.share')
        </div>
    </div>
</div>
<div class="info_single col-xs-12 ficha-puja-o">
    <?php /*
    <div class="col-xs-12 precio_minimo-text">
        <span class="min">{{ trans(\Config::get('app.theme').'-app.subastas.price_minim') }}</span>
                        <span class="min precio_minimo_alcanzado hidden">
                               {{ trans(\Config::get('app.theme').'-app.subastas.reached') }}
                        </span>
                         <span class="min precio_minimo_no_alcanzado hidden">
                               {{ trans(\Config::get('app.theme').'-app.subastas.no_reached') }}
                        </span>
    </div>
     
     */
    ?>
    <div class="col-xs-12"></div>
        <div class="col-lg-12">
            <div class="info_single_title hist_new <?= !empty($data['js_item']['user']['ordenMaxima'])?'':'hidden'; ?> ">
                {{trans(\Config::get('app.theme').'-app.lot.max_puja')}} 
                <strong>
                    <span id="tuorden">
                        @if ( !empty($data['js_item']['user']['ordenMaxima'])) 
                            {{ $data['js_item']['user']['ordenMaxima']}}
                        @endif
                    </span>
                    {{trans(\Config::get('app.theme').'-app.subastas.euros')}}
                </strong>
            </div>
        </div>
        <div class="col-xs-12 precio_minimo-text">
            <div class="info_single_content">
                <div id="text_actual_max_bid" class=" <?=  count($lote_actual->pujas) >0? '':'hidden' ?>">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }} <strong><span id="actual_max_bid" >{{ $lote_actual->formatted_actual_bid }} €<span></strong> </div>
                <div  id="text_actual_no_bid" class=" <?=  count($lote_actual->pujas) >0? 'hidden':'' ?>"> {{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </div>
                @if (count($lote_actual->pujas) >0)
                    <small class='explanation_bid t_insert' style="font-size:11px;">{{ trans(\Config::get('app.theme').'-app.lot.next_min_bid') }}  <strong><span class="siguiente_puja"> </span></strong> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </small>
                @else
                    <small class='explanation_bid t_insert' style="font-size:11px;">{{ trans(\Config::get('app.theme').'-app.lot.min_puja') }}  <strong><span class="siguiente_puja"> </span></strong> {{ trans(\Config::get('app.theme').'-app.subastas.euros') }} </small> 
                @endif

                @if (isset($lote_actual->impres_asigl0) && $lote_actual->impres_asigl0 > 0 && Session::has('user'))
                <div class="pre_min">
                    <br><br>
                    <big>
                        {{ trans(\Config::get('app.theme').'-app.subastas.price_minim') }}: 
                        <span class="precio_minimo_alcanzado verde hidden">{{ trans(\Config::get('app.theme').'-app.subastas.reached') }}</span>
                        <span class="precio_minimo_no_alcanzado rojo hidden">{{ trans(\Config::get('app.theme').'-app.subastas.no_reached') }}</span> 
                    </big>
                </div>            
                @endif

                <div class="insert_bid">
                    <p class="text-right"><strong>{{ trans(\Config::get('app.theme').'-app.lot.insert_max_puja') }}</strong></p>
                </div>
                <div class="input-group col-xs-12">
                    <input style="font-style: normal;" id="bid_amount" placeholder="{{ $data['precio_salida'] }}" class="form-control control-number" type="text" value="{{ $data['precio_salida'] }}">
                    <div class="input-group" style="display: block;">
                        <button type="button" data-from="modal" class="lot-action_pujar_on_line btn btn-custom <?= Session::has('user')?'add_favs':''; ?>" type="button" ref="{{ $lote_actual->ref_asigl0 }}" ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}" >{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
                                
                    </div>
                        @if(Session::has('user') &&  $lote_actual->retirado_asigl0 =='N')
                                <a  class="btn btn-add-fav <?= $data['subasta_info']->lote_actual->favorito? 'hidden':'' ?>" id="add_fav" href="javascript:action_fav_modal('add')">
                                    <p>{{ trans(\Config::get('app.theme').'-app.lot.add_to_fav') }} </p>
                                </a>
                                <a class="btn btn-del-fav <?= $data['subasta_info']->lote_actual->favorito? '':'hidden' ?>" id="del_fav" href="javascript:action_fav_modal('remove')">
                                    <p>{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}} </p>
                                </a>  
                                @endif
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
        $("#actual_max_bid").bind('DOMNodeInserted', function(event) {
            if (event.type == 'DOMNodeInserted') {

               $.ajax({
                    type: "GET",
                    url:  "/lot/getfechafin",
                    data: { cod: cod_sub, ref: ref},
                    success: function( data ) {         
                        reloadPujasList();
                        if (data.status == 'success'){                    
                           $(".timer").data('ini', new Date().getTime());
                           $(".timer").data('countdown',data.countdown); 
                            

                        }


                    }
                });
            } 
        });
    });
</script>

        

