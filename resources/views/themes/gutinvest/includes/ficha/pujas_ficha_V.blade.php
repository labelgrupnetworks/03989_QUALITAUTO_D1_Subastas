<div class="info_single ficha_V col-xs-12">
    <div class="info_single_title col-xs-12">
        <div class="sub-o">
            
           <?php // entraran tanto lotes de subastas V como cerrados de otra con posibilidad de compra  ?>
                       
            <p>
                @if ($lote_actual->tipo_sub == 'V')
                    {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_venta') }}
                @elseif($lote_actual->tipo_sub == 'W')
                    {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_presencial') }}
                @elseif($lote_actual->tipo_sub == 'O')
                    {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_online') }}
                @endif
            </p>
            
            <div class="date_top_side_small">     
            @if($lote_actual->cerrado_asigl0 == 'N')
                <span class="cierre_lote"></span>
            @endif
          <?php /* no ponemos CET   <span id="cet_o"> {{ trans(\Config::get('app.theme').'-app.lot.cet') }}</span> */ ?>
        </div>
        </div>
         <div class="col-xs-12 no-padding">
        <div class="clock-ficha">
            @if($lote_actual->cerrado_asigl0 == 'N')
                <span class="clock"><i class="fa fa-clock-o"></i><span data-countdown="{{ strtotime($lote_actual->end_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($lote_actual->end_session); ?>" data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"></span></span>
            @endif
        </div>
        
    </div>
        
    </div>
   
    
    <div class="col-xs-12 col-sm-12">
        <div class="exit-price col-xs-12">
            <p class="pre text">
                {{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}
            </p>
        
        <div class="pre">{{$lote_actual->formatted_actual_bid}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
        </div>
    </div>
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
    <div class="col-xs-12 info_single">
        @if ($lote_actual->retirado_asigl0 == 'N' && empty($lote_actual->himp_csub) && ($lote_actual->subc_sub == 'S' || $lote_actual->subc_sub == 'A'))
        <div class="info_single_content info_single_button">
            <button data-from="modal" class="lot-action_comprar_lot btn btn-lg btn-custom" type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> {{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</button>
        </div>
        @endif
        @if(Session::has('user') &&  $lote_actual->retirado_asigl0 =='N')
            <a  class="btn btn-add-fav <?= $data['subasta_info']->lote_actual->favorito? 'hidden':'' ?>" id="add_fav" href="javascript:action_fav_modal('add')">
                <p>{{ trans(\Config::get('app.theme').'-app.lot.add_to_fav') }} </p>
            </a>
            <a class="btn btn-del-fav <?= $data['subasta_info']->lote_actual->favorito? '':'hidden' ?>" id="del_fav" href="javascript:action_fav_modal('remove')">
                <p>{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}} </p>
            </a>  
        @endif
    </div>


<script>
   $(document).ready(function() {
        //calculamos la fecha de cierre
     //   $("#cierre_lote").html(format_date_large(new Date("{{$lote_actual->end_session}}".replace(/-/g, "/"))),"");
        $(".cierre_lote").html(format_date_large(new Date("{{$lote_actual->end_session}}".replace(/-/g, "/")),''));

    });   
</script>  
        


				
