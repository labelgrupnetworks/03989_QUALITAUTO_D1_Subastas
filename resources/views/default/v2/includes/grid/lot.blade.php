<div class="card lot-card" {!! $codeScrollBack !!}>
	@include('includes.grid.labelLots')

	<a {!! $url !!}>
		<img class="card-img-top" src="{{ $img }}" alt="{{ $titulo }}" loading="{{ $loop->iteration > 6 ? 'lazy' : 'auto' }}">
	</a>

	<div class="card-body">
	  <h6 class="card-title max-line-2">{!! strip_tags($titulo) !!}</h6>

		<div class="lot-prices">
		@if(!$retirado && !$devuelto)
			<p class="lot-salida-price">
				@if(!$subasta_make_offer)
					@if($subasta_venta)
						<span>{{ trans('web.subastas.price_sale') }}</span>
					@else
						<span>{{ trans('web.lot.lot-price') }}</span>
					@endif

					<span>{{$precio_salida}}  {{ trans('web.subastas.euros') }}</span>
				@endif
			</p>

			@if(($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)
			<p class="lot-actual-bid">
				<span>{{ trans('web.lot.puja_actual') }}</span>
				<span class="{{$winner}}">{{ $maxPuja }} {{ trans('web.subastas.euros') }}</span>
			</p>
			@endif

			@if( $awarded && !$devuelto && !$retirado)
				@if($cerrado && $remate &&  (!empty($precio_venta) ) || ($sub_historica && !empty($item->impadj_asigl0)) )
					@if($sub_historica && !empty($item->impadj_asigl0))
						@php($precio_venta = $item->impadj_asigl0)@endphp
					@endif
					<p class="lot-buy-to">
						<span>{{ trans('web.subastas.buy_to') }}</span>
						<span>{{ $precio_venta }} {{ trans('web.subastas.euros') }}</span>
					</p>

				@elseif($cerrado &&  empty($precio_venta) && !$compra)
					<p class="lot-not-buy">
						<span class="salida-title notSold">{{ trans('web.subastas.dont_buy') }}</span>
					</p>
				@endif
			@endif
		@endif
		</div>

		@if (!$devuelto && !$retirado && !$sub_historica)
			@if($cerrado &&  empty($precio_venta) && $compra)
				<a {!! $url !!} class="btn btn-block btn-lb-primary lot-btn">{{ trans('web.subastas.buy_lot') }}</a>
			@elseif($subasta_venta  && !$cerrado)
				@if(!$end_session)
				<a {!! $url !!} class="btn btn-block btn-lb-primary lot-btn">{{ trans('web.subastas.buy_lot') }}</a>
				@endif
			@elseif(!$cerrado )
				<a {!! $url !!} class="btn btn-block btn-lb-primary lot-btn">{{ trans('web.lot.pujar') }}</a>
			@endif
		@endif
	</div>

	<div class="card-footer">

	</div>

</div>
