

<div class="{{$class_square}} square hidden">

    <a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?> >
        @if( $retirado)
            <div class="retired ">
                {{ trans(\Config::get('app.theme').'-app.lot.retired') }}
            </div>
        @elseif($fact_devuelta)
            <div class="retired" style="font-size: 10px">
                {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
            </div>
        @elseif($cerrado || ($awarded && $cerrado) &&  ($vendido || ($sub_historica && !empty($item->impadj_asigl0)) ))
            <div class="retired">
                {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
            </div>
        @endif
        <div class="item_lot">
            <div class="item_img mb-1">
                <div data-loader="loaderDetacados" class='text-input__loading--line'></div>
                <img class="img-responsive lazy" style="display: none;" data-src="{{$img}}" alt="{{$titulo}}">
            </div>

            <div class="data-container">
                    <div class="title_item">
                        <span class="seo_h4 color-brand" style="text-align: center;">{{ trans("$theme-app.lot.lot-name") }} {{ $item->ref_asigl0 }} - {{ $titulo }}</span>
                    </div>

                 <div class="desc_lot mt-2 text-center">
                    <?= $item->desc_hces1 ?>
                 </div>

            <div class="data-price text-center">
                @if( !$retirado && !$fact_devuelta)
					@if($subasta_venta)
						<?php /*
                        <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
						<p class="salida-title">{{$item->formatted_actual_bid}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
						*/?>
					@else
						@if(!$cerrado)
                        <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                        <p class="salida-title">{{\Tools::moneyFormat( $item->imptas_asigl0, false, 2) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
						@endif
                    @endif
                    <?php /*
                    @if(($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)
                                <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                                <p class="{{$winner}}">{{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>

                    @elseif ($subasta_web && $subasta_abierta_O && !empty($item->open_price) && !$cerrado  )
                            <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                            <p class="{{$winner}}">{{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                    @endif


                    @if( $awarded || $devuelto)
                            @if($devuelto)
                                <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                                <div class="salida-price"></div>
                            @elseif($cerrado && $remate &&  (!empty($precio_venta) || $sub_historica && !empty($item->impadj_asigl0)) )
                                @if($sub_historica && !empty($item->impadj_asigl0))
                                    @php($precio_venta = $item->impadj_asigl0)
                                @endif
                                <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</p> <div class="salida-price">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                            @elseif($cerrado &&   $vendido )
                                <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                                <div class="salida-price"></div>
                            @elseif($cerrado &&  !$vendido )
                                <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
                                <div class="salida-price"></div>

                            @else
                                {{-- <p class="salida-title extra-color-one"></p>
                                <div class="salida-price"></div> --}}
                            @endif
                        @endif
*/?>

                    @endif

                </div>

                <p class="mt-2 d-flex align-items justify-content-center btn-pujar-itemhome">
				@if(in_array($item->sub_hces1, ['']))
					<a style="font-size: 20px" class="button-principal carousel-pujar" <?= $url ?>>{{ trans(\Config::get('app.theme').'-app.lot.auction_closed_envelope') }}</a>
				@elseif($subasta_online && strtotime($item->start_session) > time())
					<a class="button-principal carousel-pujar" <?= $url ?>>{{ trans(\Config::get('app.theme').'-app.subastas.proximamente') }}</a>
                @elseif($subasta_online && !$cerrado)
                    <a class="button-principal carousel-pujar" <?= $url ?>>{{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}</a>
				@elseif(!$cerrado)
					<a class="button-principal carousel-pujar" <?= $url ?>>{{ trans(\Config::get('app.theme').'-app.subastas.ask_information') }}</a>
                @endif
				</p>

				<p>
					@if($subasta_online && !$cerrado)
					<span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
					@endif
				</p>

            </div>

        </div>
    </a>
</div>
