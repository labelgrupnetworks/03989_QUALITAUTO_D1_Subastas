

<div class="{{$class_square}} square">
    <a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?> >
        @if( $retirado)
            <div class="retired ">
                {{ trans(\Config::get('app.theme').'-app.lot.retired') }}
            </div>
        @elseif($fact_devuelta)
            <div class="retired" style="font-size: 10px">
                {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
            </div>
        @elseif($awarded && $cerrado && $vendido || ($sub_historica && !empty($item->impadj_asigl0)))
            <div class="retired">
                {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
            </div>
        @endif
        <div class="item_lot">
            <div class="item_img">
                <div data-loader="loaderDetacados" class='text-input__loading--line'></div>
                <img class="img-responsive lazy" style="display: none;" data-src="{{$img}}" alt="{{$titulo}}">
            </div>

            <div class="data-container">
                    <div class="title_item">
                        <span class="seo_h4" style="text-align: center;">{{ $titulo}}</span>
                    </div>

                 <div class="desc_lot">
                    <?= $item->descweb_hces1 ?>
                 </div>
            <div class="data-price text-center">
                @if( !$retirado && !$fact_devuelta)
                    @if($subasta_venta)
                        <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
                        <p>{{$item->formatted_actual_bid}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                    @else
                        <p class="salida-title" style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden'}}" >{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                        <p style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden'}}">{{$item->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>

                    @endif
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
                            @elseif($cerrado && $remate &&  !empty($precio_venta) || ($sub_historica && !empty($item->impadj_asigl0)) )
                                @if($sub_historica && !empty($item->impadj_asigl0))
                                    @php($precio_venta = $item->impadj_asigl0)
                                @endif
                                <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</p> <div class="salida-price">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                            @elseif($cerrado && $vendido)
                            <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</p>
                                <div class="salida-price">{{ $item->formatted_actual_bid }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                            @elseif($cerrado &&  empty($precio_venta))
                                <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
                                <div class="salida-price"></div>

                            @else
                                {{-- <p class="salida-title extra-color-one"></p>
                                <div class="salida-price"></div> --}}
                            @endif
                        @endif


                    @endif

                </div>
                <div class="text-center">
                    @if($awarded && !$retirado && !$sub_cerrada && !$fact_devuelta)
                        @if(!$cerrado  && ($subasta_online || $subasta_web))
                            <button  class="lotlist-button-buy" type="button">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
                        @elseif (!$vendido && ( (!$cerrado && $subasta_venta) || ( $cerrado && $item->compra_asigl0 == 'S'  && ($subasta_online || $subasta_web) ) ) )
                            <button  class="lotlist-button-buy" type="button">{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</button>
                        @endif
                    @endif
                </div>
                @if($subasta_online && !$cerrado)

                            <p class="salida-time">
                                <i class="fa fa-clock-o"></i>
                                <span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
                            </p>
                        @endif

            </div>

        </div>
    </a>
</div>
