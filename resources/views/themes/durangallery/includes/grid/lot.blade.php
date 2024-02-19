

<div class="{{$class_square}} square" {!! $codeScrollBack !!}>



        <div class="item_lot">

			@if($subasta_online && !$cerrado)
				<p class="salida-time">
					<i class="fa fa-clock-o"></i>
					<span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
				</p>
			@endif

            <div class="item_img">
                <a title="{{ $titulo }}" {!! $url !!}><img class="img-responsive " src="{{$img}}" alt="{{ $titulo }}"></a>
            </div>

            <div class="data-container">

				{{-- las etiquetas van a parta para simplificar el código --}}
				@include('includes.grid.labelLots')

				{{-- Título y botones --}}
				<div class="title_item d-flex">
						<p class="text-left seo_h4 max-line-1" style="flex: 4">
							<a title="{{ $titulo }}" {!! $url !!}>{!! strip_tags($titulo) !!}</a>
						</p>

						<p class="text-right" style="flex: 1">
							@if (Session::has('user'))
								<a href="javascript:" class="fav_element">
									<i class="fa {{ !empty($item->id_web_favorites) ? 'fa-heart' : 'fa-heart-o' }}" aria-hidden="true"
										data-cod_sub="{{$item->cod_sub}}" data-ref="{{$item->ref_asigl0}}"
										data-action="{{ !empty($item->id_web_favorites) ? 'remove' : 'add' }}"
										@if(Session::has('user')) data-cod_licit="0" @endif>
									</i>
								</a>
							@endif
							<a {!! $url !!}><i class="fa fa-shopping-bag" aria-hidden="true"></i></a>
						</p>
				</div>

				{{-- Descripción --}}
				<div class="description_item d-flex">
					<p class="max-line-1" style="flex: 3">
						{!! $item->artist !!}
					</p>

					<div class="data-price">
						<p style="flex: 1">
							@if(!$retirado && !$devuelto && !$cerrado)
								{{-- Precio de salida --}}
								<span class="salida-price" style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden'}}">
									@if($compra)
										{{  \Tools::moneyFormat(\Tools::PriceWithTaxForEuropean($item->impsalhces_asigl0,\Session::get('user.cod')),false,2) }}  {{ trans($theme.'-app.subastas.euros') }}
									@else
										{{ trans($theme.'-app.galery.request_information') }}
									@endif

								</span>


								{{-- No tienen subasta --}}
								{{-- @if(($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)
								<p>
									<span class="salida-title">{{ trans($theme.'-app.lot.puja_actual') }}</span>
									<span class="salida-price {{$winner}}">{{ $maxPuja }} {{ trans($theme.'-app.subastas.euros') }}</span>
								</p>
								@endif --}}

								{{-- vendido --}}
								@if($awarded && !$devuelto && !$retirado)
									@if($cerrado && $remate &&  (!empty($precio_venta) ) || ($sub_historica && !empty($item->impadj_asigl0)) )
										@if($sub_historica && !empty($item->impadj_asigl0))
											@php($precio_venta = $item->impadj_asigl0)@endphp
										@endif
										<span class="salida-title soldGrid">
											{{ trans($theme.'-app.subastas.buy') }}
										</span>
									@endif
								@endif
							@endif
						</p>
					</div>

				</div>

            </div>

        </div>

</div>
