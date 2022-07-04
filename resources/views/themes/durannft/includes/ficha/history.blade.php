<?php
    //Mostrar el historico de pujas del lote
    $cont=0;
    //si se ha superado el precio minimo
    $min_price_surpass = (count($lote_actual->pujas) >0 && $lote_actual->actual_bid >=  $lote_actual->impres_asigl0  );
    $num_pujas = count($data['js_item']['lote_actual']->pujas);
    $view_num_pujas = !empty(Config::get('app.max_bids'))? Config::get('app.max_bids')  : 9999;
?>
<input id="view_num_pujas" type="hidden" value="{{$view_num_pujas}}">
<input id="view_all_pujas_active" type="hidden" value="0">
<div id="historial_pujas" class="hist col-xs-12 no-padding  mb-2 <?= ($num_pujas == 0) ? 'hidden' : '' ?>">
        <div class="hist_title col-xs-12 no-padding ">
                {{ trans(\Config::get('app.theme').'-app.lot.history') }} ( <span class="num_pujas"></span> {{ trans(\Config::get('app.theme').'-app.lot.bidding') }})
        </div>
        <div class="hist_content col-xs-12 no-padding" id="pujas_list" >

        </div>
</div>


<script>
    $(document).ready(function() {
        //Cargamos el listado de pujas
        reloadPujasList_O();
    });
</script>
