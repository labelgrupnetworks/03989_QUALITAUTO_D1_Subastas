@extends('layouts.panel')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop

@section('content')

<?php
$all_adj = array();
$sub = new \App\Models\Subasta;
$all_adj=array();
$tipo_pago_global = false; //cuando el tipo de pago es glogal o por subastas
foreach($data['adjudicaciones'] as $temp_adj){
    $all_adj[$temp_adj->cod_sub]['lotes'][]=$temp_adj;
}
foreach($all_adj as $key_inf => $value){
    $sub->cod = $key_inf;
    $all_adj[$key_inf]['inf'] = $sub->getInfSubasta();
}


$all_adj_pag=array();
foreach($data['adjudicaciones_pag'] as $temp_adj){
    $all_adj_pag[$temp_adj->cod_sub]['lotes'][]=$temp_adj;
}
foreach($all_adj_pag as $key_inf => $value){
    $sub->cod = $key_inf;
    $all_adj_pag[$key_inf]['inf'] = $sub->getInfSubasta();
}


use App\libs\Currency;
$currency = new Currency();
$divisas = $currency->getAllCurrencies();

//creo que este solo hace falta en la vista para pagar
$codPais_clid = $data['user']->codpais_clid ?? $data['user']->codpais_cli ?? 'ES';
?>
<script>
    var info_lots = @JSON($data["js_item"]);
    var currency = @JSON($divisas);
</script>


<section class="account payment">
	<div class="container">
		<div class="row">

			<div class="col-xs-12">

				{{-- Title --}}
				<div class="user-datas-title flex align-items-center mb-3">
					<p style="margin: 0px">{{ trans($theme.'-app.user_panel.my_pending_bills') }}
					</p>
					<div class="col_reg_form"></div>
					<div class="btns-pay flex">

						<select id="actual_currency">
							@foreach($divisas as $divisa)
							@if($divisa->cod_div != 'EUR')
							<option value='{{ $divisa->cod_div }}'
								<?= ($divisa->cod_div == 'USD')? 'selected="selected"' : '' ?>>
								{{ $divisa->cod_div }}
							</option>
							@endif
							@endforeach
						</select>

					</div>
				</div>

				{{-- Pendientes --}}
				<div class="title-collapse" data-toggle="collapse" data-target="#pendings_bills">
					<p>
						{{ trans($theme.'-app.user_panel.still_paid') }}
						<span style="float: right"><i class="fa fa-caret-right" aria-hidden="true"></i></span>
					</p>
				</div>

				<div class="collapse js-title-collapse" id="pendings_bills">
					<div class="panel-group" id="accordion">
						<div class="panel panel-default">
							@foreach($all_adj as $key_sub => $all_inf)
							@include('pages.panel.adjudicaciones.subasta-adjudicaciones', ['all_inf' => $all_inf,
							'pagada' => false])
							@endforeach
						</div>
					</div>
				</div>

				{{-- Pagadas --}}
				<div class="title-collapse" data-toggle="collapse" data-target="#bills">
					<p>
						{{ trans($theme.'-app.user_panel.bills') }}
						<span style="float: right"><i class="fa fa-caret-right" aria-hidden="true"></i></span>
					</p>
				</div>

				<div class="collapse js-title-collapse" id="bills">
					<div class="panel-group" id="accordion">
						<div class="panel panel-default">
							@foreach($all_adj_pag as $key_sub => $all_inf)
							@include('pages.panel.adjudicaciones.subasta-adjudicaciones', ['all_inf' => $all_inf,
							'pagada' => true])
							@endforeach
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	@include('pages.panel.adjudicaciones.modal_shipment_tracking');
</section>

<!-- Modal -->
<div class="modal fade modal-payment" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">{{ trans("$theme-app.user_panel.wire_transfer") }}</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-md-6 fs-18">
						<p>{{ trans("$theme-app.shopping_cart.total_pay") }}<br><span id="modal-pagar">0</span> <span>€</span></p>
					</div>
					<div class="col-xs-12 col-md-6 fs-18">
						<p>{{ trans("$theme-app.user_panel.concept") }}<br><span id="modal-concepto">XXX</span></p>
					</div>
				</div>
				<div class="row mt-2">
					<div class="col-xs-12">
						{!! trans("$theme-app.user_panel.transfer_content") !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<script>

$( document ).ready(function() {
	//actualiza las divisas
	$("#actual_currency").trigger('change');

	$('.custom-wrapper').on('click', function(e){
		if(e.target.nodeName == 'A'){
			return;
		}
		this.classList.toggle('expand');
	});

	$('.js-title-collapse').on('show.bs.collapse', function (e) {
		$(`[data-target^='#${e.target.id}'] i.fa.fa-caret-right`).removeClass('fa-caret-right').addClass('fa-caret-down');
	});

	$('.js-title-collapse').on('hide.bs.collapse', function (e) {
		$(`[data-target^='#${e.target.id}'] i.fa.fa-caret-down`).removeClass('fa-caret-down').addClass('fa-caret-right');
	});

	$('.js-btn-certificate').on('click', function(){


		let data = {
			'_token': '{{ csrf_token() }}',
			'cod_sub': this.dataset.codsub,
			'ref_asigl0': this.dataset.ref
		};

		$.ajax({
			type: "POST",
			url: "{{ route('panel.allotment.certifiacte', ['lang' => Config::get('app.locale')]) }}",
			data: data,
			success: function(response){
				var a = document.createElement("a");
  				a.href = response;
  				a.setAttribute("download", `${data.cod_sub}-${data.ref_asigl0}`);
  				a.click();
			},
			error: function(response){
				console.log(response);
			}
		});

	});

	$('#largeModal').on('show.bs.modal', function (event) {
		const button = $(event.relatedTarget);
  		const concept = button.data('concept');
		const codsub = button.data('codsub');
  		const modal = $(this);

		const price = $(`#total_pagar_${codsub}`).text();

  		modal.find('#modal-concepto').text(concept);
		modal.find('#modal-pagar').text(price);
	});

	reload_carrito();

});
</script>


@stop
