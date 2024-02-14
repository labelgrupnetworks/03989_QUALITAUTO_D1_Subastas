<?php
    //Mostrar el historico de pujas del lote
    $cont=0;
    //si se ha superado el precio minimo
    $min_price_surpass = (count($lote_actual->pujas) >0 && $lote_actual->actual_bid >=  $lote_actual->impres_asigl0  );
    $num_pujas = count($data['js_item']['lote_actual']->pujas);
    $view_num_pujas = Config::get('app.max_bids');
    if(empty(Config::get('app.max_bids'))){
        $view_num_pujas = 9999;
    }
?>
<input id="view_num_pujas" type="hidden" value="{{$view_num_pujas}}">
<input id="view_all_pujas_active" type="hidden" value="0">
<div id="historial_pujas" class="historial-content hist  <?= ($num_pujas == 0) ? 'hidden' : '' ?>">
        <div class="hist_title historial-title">
            <p>{{ trans($theme.'-app.lot.history') }} (<span class="num_pujas"></span> {{ trans($theme.'-app.lot.bidding') }})</p>
        </div>
        <div class="hist_content historial-list" id="pujas_list" >

        </div>
</div>


<script>
    $(document).ready(function() {
        //Cargamos el listado de pujas
        reloadPujasList_O();
    });
</script>
