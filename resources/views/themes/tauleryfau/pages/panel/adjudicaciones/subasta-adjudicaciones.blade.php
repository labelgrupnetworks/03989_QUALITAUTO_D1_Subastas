<a aria-expanded="true" data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}_{{$pagada}}">
	<div class="panel-heading">
		<h4 class="panel-title">
			{{$all_inf['inf']->name}}
		</h4>
		<i class="fas fa-sort-down"></i>
	</div>
</a>

<div id="{{$all_inf['inf']->cod_sub}}_{{$pagada}}" class="table-responsive-custom panel-collapse collapse">

	{{-- Cabeceras --}}
	<div class="custom-head-wrapper flex">
		<div class="table-data-check flex hidden">

		</div>
		<div class="img-data-customs flex "></div>
		<div class="lot-data-custon">
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</p>
		</div>
		<div class="name-data-custom">
			<p style="font-weight: 900">{{ trans(\Config::get('app.theme').'-app.lot.description') }}</p>
		</div>
		<div class="remat-data-custom">
			<p>{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</p>
		</div>
	</div>


	@foreach($all_inf['lotes'] as $inf_lot)
	@php
		$url_friendly = str_slug($inf_lot->titulo_hces1);
		$url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
		$precio_remapte = $inf_lot->himp_csub;
	@endphp


	{{-- Lotes --}}
	<div class="custom-wrapper valign">

		<div class="img-dat img-data-customs flex valign" role="button" id="" style="grid-area: image;">
			<img class="img-responsive" src="/img/load/lote_medium/{{ $inf_lot->imagen }}">
		</div>

		<div class="account-lot-wrapper font-data-custom" style="grid-area: lot;">
			<p><span class="visible-expand">{{ trans(\Config::get('app.theme').'-app.lot.lot-name') }}</span> {{$inf_lot->ref_asigl1}}</p>
		</div>

		<div class="description" style="grid-area: description;">
			{!! $inf_lot->desc_hces1 !!}
		</div>

		<div class="d-flex align-items-center justify-content-space-between buttons-price-wrapper"
			style="grid-area: price;">
			<div>
				<p class="visible-expand" style="font-weight: 900">
					{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</p>
				<p class="font-data-custom">
					{{ \Tools::moneyFormat($precio_remapte,false,2) }}
					{{ trans(\Config::get('app.theme').'-app.lot.eur') }}
					&nbsp;|&nbsp;<span value="{{$precio_remapte}}" class="js-divisa"></span>
				</p>
			</div>

			@if (!$pagada)
			<a
				class="btn btn-color btn-puja-panel btn-disabled d-flex align-items-center justify-content-center">{{ trans(\Config::get('app.theme').'-app.user_panel.high_quality_photography') }}</a>
			<a
				class="btn btn-color btn-puja-panel btn-disabled d-flex align-items-center justify-content-center">{{ trans(\Config::get('app.theme').'-app.user_panel.certificate') }}</a>
			@else
			<a
				href="/img/load/real/{{ $inf_lot->imagen }}" download="{{$inf_lot->ref_asigl0}}_{{ $inf_lot->name }}" alt="{{$inf_lot->titulo_hces1}}"
				class="btn btn-color btn-puja-panel btn-gold d-flex align-items-center justify-content-center">{{ trans(\Config::get('app.theme').'-app.user_panel.high_quality_photography') }}</a>
			<a data-codsub="{{$inf_lot->cod_sub}}" data-ref="{{$inf_lot->ref_asigl0}}"
				class="btn btn-color btn-puja-panel btn-blue d-flex align-items-center justify-content-center js-btn-certificate">{{ trans(\Config::get('app.theme').'-app.user_panel.certificate') }}</a>
			@endif
		</div>

		<div class="slick-arrow" style="grid-area: arrow;">
			<p>←</p>
		</div>

	</div>
	@endforeach


	{{-- Facturas PDF --}}
	<div class="text-right factura-buttons">
		@if (!$pagada)

			@if (!empty($all_inf['lotes'][0]->prefactura))
				<a href="/prefactura/{{ $all_inf['inf']->cod_sub }}" download class="btn btn-color factura-button mb-1">{{ trans(\Config::get('app.theme').'-app.user_panel.proforma_invoice') }}</a>
			@endif

			@if($all_inf['inf']->compraweb_sub == 'S')
				<input id="shipping_express" type="radio" name="shipping" value="express" checked="checked" style="display: none">
				<span id="total_pagar_{{$all_inf['inf']->cod_sub}}" class='hidden precio_final_{{$all_inf['inf']->cod_sub}}'></span>
				<a class="btn btn-color btn-gold mb-1" data-toggle="modal" data-target="#largeModal" data-codsub="{{$all_inf['inf']->cod_sub}}" data-concept="{{explode("-", $all_inf['inf']->name)[0] }}-{{\Session::get('user.cod')}}">{{ trans("$theme-app.user_panel.bank_transfer") }}</a>

				<a class="btn btn-color btn-blue mb-1" href="{{ route('panel.allotment.sub', ['cod_sub' => $all_inf['inf']->cod_sub, 'lang' => Config::get('app.locale')]) }}"
				cod_sub="{{$all_inf['inf']->cod_sub}}">{{ trans(\Config::get('app.theme').'-app.user_panel.pay_now') }}</a>
			@endif

		@else

			<a class="btn btn-color btn-blue mb-1 js-btn-shipment" data-afral_csub="{{$all_inf['lotes'][0]->afral_csub}}" data-nfral_csub="{{$all_inf['lotes'][0]->nfral_csub}}"
				cod_sub="{{$all_inf['inf']->cod_sub}}" data-toggle="modal" data-target="#modal_shipment">
				{{ trans(\Config::get('app.theme').'-app.user_panel.shipment_tracking') }}
			</a>

			@if (!empty($all_inf['lotes'][0]->factura))
				<a href="/factura/{{$all_inf['lotes'][0]->afral_csub.'-'.$all_inf['lotes'][0]->nfral_csub}}" download class="btn btn-color factura-button mb-1">{{ trans(\Config::get('app.theme').'-app.user_panel.invoice_pdf') }}</a>
			@endif

		@endif
	</div>

</div>
