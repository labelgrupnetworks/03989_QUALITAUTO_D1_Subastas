

<div class="square  {!! $codeScrollBack !!}">
	 	<a class="lote-destacado-link  secondary-color-text" <?= $url?> >

		{{-- las etiquetas van a parta para simplificar el código --}}
		@include('includes.grid.labelLots')
        <div class="item_lot">
			@if($subasta_online && !$cerrado)
				<p class="salida-time">
					<i class="fa fa-clock-o"></i>
					<span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
				</p>
			@endif
            <div class="item_image_cover">
                <img class="img-responsive " src="{{$img}}" alt="{{$titulo}}">
			</div>

			@if(!$subasta_venta)
			<div class="lot_title">
				Lote {{ $item->ref_asigl0 }}
			</div>
			@endif

            <div class="data-container">
                    <div class="title_item max-line-2">
                        <span class="seo_h4">{{ $titulo }}</span>
                    </div>
					<div class="data-price text-center">

						@if(in_array($auction->cod_sub, array_keys($subastasExternas)))

						<p>
							<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
							<span class="salida-price">{{ $precio_salida }} {{ $divisas[$subastasExternas[$auction->cod_sub]]->symbolhtml_div }}</span>
						</p>

						@elseif( !$retirado && !$devuelto)
							<p>
								@if($subasta_venta)
									<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</span>
								@else
									<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</span>
								@endif
								<span class="salida-price">{{$precio_salida}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
							</p>

							@if(($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)
							<p>
								<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
								<span class="salida-price {{$winner}}">{{ $maxPuja }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
							</p>
							@else
							<p style="visibility: collapse">
								<span class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</span>
								<span class="salida-price"></span>
							</p>
							@endif
							@if( $awarded && !$devuelto && !$retirado)
								@if($cerrado && $remate &&  (!empty($precio_venta) ) || ($sub_historica && !empty($item->impadj_asigl0)) )
									@if($sub_historica && !empty($item->impadj_asigl0))
										@php($precio_venta = $item->impadj_asigl0)
									@endif
									<p class="awarded-wrapper">
										<span class="salida-title soldGrid">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</span>
										<span class="salida-price  soldGrid">{{ $precio_venta }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
									</p>

								@elseif($cerrado &&  empty($precio_venta) && !$compra)
									<p class="awarded-wrapper">
										 <span class="salida-title notSold">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</span>
									</p>
								@endif
							@endif
					@endif
				</div>

			</div>

			<div class="btn-lot-lotlist d-flex" style="flex:1">
				@if (!$devuelto && !$retirado && !$sub_historica)
					@if($cerrado &&  empty($precio_venta) && $compra)
						<p class="btn-bid-lotlist w-100">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</p>
					@elseif($subasta_venta  && !$cerrado )
						<p class="btn-bid-lotlist w-100">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</p>
					@elseif(!$cerrado )
						<p class="btn-bid-lotlist w-100">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</p>
					@endif
				@endif
			</div>

				<?php
					$path = "/files/".Config::get('app.emp')."/$item->num_hces1/$item->lin_hces1/files/";
					$files = [];
					if(is_dir(getcwd() . $path)){
						$files = array_slice(scandir(getcwd() . $path), 2);
					}
				?>

				<div class="extra-buttons-wrapper d-flex justify-content-space-between">

					<div class="content-extra-wrapper">
						@if (count($videos ?? []) > 0)
						<p class="content-extra"><i class="fa fa-youtube-play" aria-hidden="true"></i></p>
						@endif

						@if ($files)
							<p class="content-extra"><i class="fa fa-plus" aria-hidden="true"></i> INFO</p>
						@endif
					</div>

					@if(!$subasta_venta)
					<div class="d-flex icons-lot">
						<div class="d-flex bids-icons">
							<img src="/default/img/icons/hammer.png" width="14px" height="14px">
							<p>{{ $bids }}</p>
						</div>
						<div class="d-flex align-items-center">
							<p><i class="fa fa-user" aria-hidden="true"></i> {{ $licits }}</p>
						</div>


					</div>
					@endif

				</div>

        </div>
    </a>
</div>
