@php
$isNotRetired = !$devuelto && !$retirado;
@endphp
<div class="card lot-card" {!! $codeScrollBack !!}>
	@include('includes.grid.labelLots')

	<a {!! $url !!}>
		<img class="card-img-top" src="{{ $img }}" alt="{{ $titulo }}" loading="{{ $loop->iteration > 6 ? 'lazy' : 'auto' }}">
	</a>

	<div class="card-body">

		<h2>
			{{ trans("$theme-app.lot.lot-name") }} {{$item->ref_asigl0}}

			@if (count($videos ?? []) > 0)
			<div class="float-end">
				@include('components.boostrap_icon', ['icon' => 'youtube', 'size' => '36', 'color' => '#666'])
			</div>
			@endif
		</h2>

	  	<h5 class="card-title max-line-2">{!! strip_tags($titulo) !!}</h5>

		<div class="lot-prices">
		@if($isNotRetired)
			<h4 class="lot-salida-price text-lb-gray">
				@if(!$subasta_make_offer)
					<span>
						{{ $subasta_venta ? trans("$theme-app.subastas.price_sale") : trans("$theme-app.lot.lot-price") }}
					</span>

					<span>{{$precio_salida}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
				@endif
			</h4>

			@if(($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)
			<h4 class="lot-actual-bid">
				<span>{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
				<span class="{{$winner}}">{{ $maxPuja }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
			</h4>
			@endif

			@if($awarded)
				@if($cerrado && $remate &&  (!empty($precio_venta) ) || ($sub_historica && !empty($item->impadj_asigl0)) )
					@if($sub_historica && !empty($item->impadj_asigl0))
						@php($precio_venta = $item->impadj_asigl0)@endphp
					@endif
					<h4 class="lot-buy-to">
						<span>{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</span>
						<span>{{ $precio_venta }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
					</h4>
				@endif
			@endif
		@endif
		</div>

	</div>

	<div class="card-footer">

		@if(!$subasta_venta)
		<div class="bidds-counter bg-lb-primary-150 text-lb-gray">
			<img src="/themes/{{$theme}}/assets/icons/hammer.svg" alt="bids">
			<h5 class="m-0">{{ $bids }}</h5>
			@include('components.boostrap_icon', ['icon' => 'person-fill', 'size' => '20'])
			<h5 class="m-0">{{ $licits }}</h5>
		</div>
		@endif

		@php
		$isAwarded = $awarded && $cerrado && $remate && (!empty($precio_venta)) || ($sub_historica && !empty($item->impadj_asigl0));
		@endphp

		@if ($isNotRetired)
			@if($isAwarded)
				<a {!! $url !!} class="btn btn-block btn-outline-lb-primary lot-btn">{{ trans("$theme-app.sheet_tr.view") }}</a>
			@elseif ($cerrado && empty($precio_venta) && !$compra || $sub_historica)
				<div class="w-100 d-flex align-items-center justify-content-center bg-lb-primary-150">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</div>
			@elseif($cerrado && empty($precio_venta) && $compra && !$sub_historica)
				<a {!! $url !!} class="btn btn-block btn-lb-primary lot-btn">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</a>
			@elseif($subasta_venta && !$cerrado)
				@if(!$end_session)
				<a {!! $url !!} class="btn btn-block btn-lb-primary lot-btn">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</a>
				@endif
			@elseif(!$cerrado)
				<a {!! $url !!} class="btn btn-block btn-lb-primary lot-btn">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</a>
			@endif
		@endif
	</div>

</div>
