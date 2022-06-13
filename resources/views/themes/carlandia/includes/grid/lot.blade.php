

<div class="{{$class_square}} square" {!! $codeScrollBack !!}>
	<a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?> >

		{{-- las etiquetas van a parta para simplificar el código --}}
		@include('includes.grid.labelLots')
        <div class="item_lot">

			@if ($subasta_online)
				<div class="salida-time online">
					<p>
					PUJAR EN <span style="text-transform: uppercase;">{{ trans("$theme-app.subastas.lot_subasta_online") }}</span>

					@if(!$cerrado)
						<br>
						<i class="fa fa-clock-o"></i>
						<span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
					@endif
					</p>
				</div>

			@elseif($subasta_venta)
				<div class="salida-time venta cliccontraofertarGrid_JS" data-matricula="{{$item->matricula}}">
					<p><span style="text-transform: uppercase;">{{ trans("$theme-app.lot.counteroffer_btn") }}</span></p>
				</div>
			@endif

            <div class="item_img">
                <img class="img-responsive " src="{{$img}}" alt="{{ $titulo }}">
            </div>

            <div class="data-container">
					<p class="m-0">{{ trans("$theme-app.lot.lot-name") . ' ' . $item->ref_asigl0 }}</p>
                    <div class="title_item">
                        <span class="seo_h4 bold" style="text-align: left;">{!! strip_tags($titulo) !!}</span>
                    </div>

					<div class="featrures-lot mt-1">
						<p>
							<span class="salida-title">Fecha Matriculación</span>
							<span class="salida-price">{{$item->matriculacion ?? '-'}}</span>
						</p>

						<p>
							<span class="salida-title">Kilometraje</span>
							<span class="salida-price">{{ !empty($item->km) ? Tools::moneyFormat($item->km) : '-' }}</span>
						</p>
					</div>

					<div class="data-price text-center">
						@if(!$retirado && !$devuelto)
							<p>
								@if($subasta_venta)
									<span class="salida-title">Rango de precios</span>
								@else
									<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
									<span class="salida-price">{{$precio_salida}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
								@endif
							</p>

							@if(($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado)
							<p>
								<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
								@if ($hay_pujas)
								<span class="salida-price {{$winner}}">{{ $maxPuja }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
								@endif
							</p>
							@elseif($subasta_venta)
								@if($item->importe_max && $item->importe_min)
								<p class="d-flex justify-content-space-between">
									<span>{{ Tools::moneyFormat($item->importe_max, trans("$theme-app.subastas.euros"), 0) }}</span>
									<span>-</span>
									<span>{{ Tools::moneyFormat($item->importe_min, trans("$theme-app.subastas.euros"), 0) }}</span>
								</p>
								@else
								<p>{{ ucfirst(mb_strtolower(trans("$theme-app.lot.cash_na"))) }}</p>
								@endif
							@endif

							@if( $awarded && !$devuelto && !$retirado)
								@if($cerrado && $remate &&  (!empty($precio_venta) ) || ($sub_historica && !empty($item->impadj_asigl0)) )
									@if($sub_historica && !empty($item->impadj_asigl0))
										@php($precio_venta = $item->impadj_asigl0)@endphp
									@endif
									<p>
										<span class="salida-title soldGrid">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</span>
										<span class="salida-price  soldGrid">{{ $precio_venta }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
									</p>

								@elseif($cerrado &&  empty($precio_venta) && !$compra)
									<p> <span class="salida-title notSold">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</span></p>
								@endif
							@endif
						@endif
					</div>


				{{-- @if (!$devuelto && !$retirado && !$sub_historica)
					@if($cerrado &&  empty($precio_venta) && $compra)
						<p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</p>
					@elseif($subasta_venta  && !$cerrado)
						@if(!$end_session)
						<p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</p>
						@endif
					@elseif(!$cerrado )
						<p class="btn-bid-lotlist">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</p>
					@endif
				@endif --}}

            </div>

			@if($subasta_venta)
			<div class="salida-time venta clicVerfichaGrid_JS" data-coche="{{$titulo}}" data-matricula="{{$item->matricula}}" {!! $url !!}>{{ trans("$theme-app.lot.see_sheet") }} <br> {{ 'HAZ TU OFERTA' }} </div>
			@else
			<div class="salida-time online" {!! $url !!}>{{ trans("$theme-app.lot.see_sheet") }} <br> {{ 'HAZ TU PUJA' }} </div>
			@endif
        </div>
    </a>
</div>
