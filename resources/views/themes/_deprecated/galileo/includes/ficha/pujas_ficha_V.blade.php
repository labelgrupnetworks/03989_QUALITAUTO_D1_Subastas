<div class="col-xs-12 no-padding ">
    <div class="info_single_title info-type-auction-title no-padding d-flex justify-content-space-between" style="margin-bottom: 20px;">
        <div class="info-type-auction"> {{ trans(\Config::get('app.theme').'-app.subastas.lot_subasta_venta') }}</div>
        @if($lote_actual->cerrado_asigl0 == 'N')
        <div class=" ficha-info-clock">
            <span class="clock">
                <i class="fas fa-clock"></i>
                <span 
                    data-countdown="{{ strtotime($lote_actual->end_session) - getdate()[0] }}"  
                    data-format="<?= \Tools::down_timer($lote_actual->end_session); ?>" 
                    data-closed="{{ $lote_actual->cerrado_asigl0 }}" class="timer"
                >
                </span>
            </span>
        </div>
        @endif
    </div>

<div class="info_single ficha_V col-xs-12 no-padding"> 
    <div class="info_single_title col-xs-12 no-padding">
        <div class="sub-o info-ficha-title">  
            <?php // entraran tanto lotes de subastas V como cerrados de otra con posibilidad de compra  ?>
            @if($lote_actual->cerrado_asigl0 == 'N')
                <div class="col-xs-12 ficha-info-close-lot">
                    <div class="date_top_side_small" >               
                        <span class="cierre_lote"></span>  
                        <?php /* no ponemos CET   <span id="cet_o"> {{ trans(\Config::get('app.theme').'-app.lot.cet') }}</span> */ ?>
                    </div>
                </div>   
            @endif
            <div class="col-xs-12 no-padding e">
                    <p class="desc-lot-profile-title "><strong>{{ trans(\Config::get('app.theme').'-app.lot.description') }}</strong></p>
                    <div class="col-xs-12 no-padding desc-lot-profile-content">
                            <p><?= $lote_actual->desc_hces1 ?></p>
                        </div> 
            </div> 
           
        </div>
        <?php 
        //lo comento por que ya aparece la descweb en el titulo
        /*                        
        <div class="col-xs-12 ficha-desc-short no-padding">
            <?= $lote_actual->descweb_hces1 ?>
        </div>
         
         */
        ?>
    </div>
    <div class="col-xs-12 no-padding ficha-info-items-buy">
        <div class="pre">
                <p class="pre-title-principal">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
                <p class="pre-price">{{$lote_actual->formatted_actual_bid}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
            </div>
            <div class="info_single_content info_single_button ficha-button-buy">
                @if ($lote_actual->retirado_asigl0 == 'N' && empty($lote_actual->himp_csub) && ($lote_actual->subc_sub == 'S' || $lote_actual->subc_sub == 'A'))
                    <button data-from="modal" class="button-principal lot-action_comprar_lot" type="button" ref="{{ $data['subasta_info']->lote_actual->ref_asigl0 }}" codsub="{{ $data['subasta_info']->lote_actual->cod_sub }}">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</button>
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
     //   $("#cierre_lote").html(format_date_large(new Date("{{$lote_actual->end_session}}".replace(/-/g, "/"))),"");
        $(".cierre_lote").html(format_date_large(new Date("{{$lote_actual->end_session}}".replace(/-/g, "/")),''));

    });   
</script>  
        


				
