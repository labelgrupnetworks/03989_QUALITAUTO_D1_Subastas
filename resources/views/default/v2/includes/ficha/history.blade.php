@php
//Mostrar el historico de pujas del lote
$cont = 0;
//si se ha superado el precio minimo
$min_price_surpass = (count($lote_actual->pujas) > 0 && $lote_actual->actual_bid >=  $lote_actual->impres_asigl0);
$num_pujas = count($lote_actual->pujas);
$view_num_pujas = !empty(Config::get('app.max_bids')) ? Config::get('app.max_bids')  : 9999;
@endphp

<input id="view_num_pujas" type="hidden" value="{{$view_num_pujas}}">
<input id="view_all_pujas_active" type="hidden" value="0">

<input id="trans_lot_i" type="hidden" value="{{ trans(\Config::get('app.theme').'-app.lot.I') }}">
<input id="trans_lot_puja_automatica" type="hidden" value="{{ trans(\Config::get('app.theme').'-app.lot.puja_automatica') }}">

@if ($lote_actual->inversa_sub=='S')
	<input id="trans_minimal_price" type="hidden" value="{{ trans(\Config::get('app.theme').'-app.lot.maximal-price') }}">
@else
	<input id="trans_minimal_price" type="hidden" value="{{ trans(\Config::get('app.theme').'-app.lot.minimal-price') }}">
@endif

<section id="historial_pujas" @class(['hidden' => $num_pujas == 0])>

	<h5 class="hist_title">
		{{ trans(\Config::get('app.theme').'-app.lot.history') }} (<span id="num_pujas" class="num_pujas"></span> {{ trim(trans(\Config::get('app.theme').'-app.lot.bidding')) }})
	</h5>
	<div class="hist card">
		<div class="hist_content lb-scroll" id="pujas_list" style="--max-lines: {{$view_num_pujas}}"></div>
	</div>
	<div id="view_more" class="more more-historic-bids hidden">
		<a title="ver todas" data-toggle="collapse" data-target="#pujas-collapse" href="javascript:view_all_bids();">
			<span id="view_more_text">{{ trans(\Config::get('app.theme').'-app.lot.see-all') }} </span>
			<span id="hide_bids_text" class="hidden">{{ trans(\Config::get('app.theme').'-app.lot.hidden') }} </span> <i class="fa fa-angle-down"></i>
		</a>
	</div>
</section>


<script>
    $(document).ready(function() {
        //Cargamos el listado de pujas
        //reloadPujasList_O();
		reloadPujasListO();
    });
</script>
