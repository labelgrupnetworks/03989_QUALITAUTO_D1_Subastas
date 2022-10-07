
<div class="{{$class_square}} large_square">
    <a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?> >
        <div class="col-xs-12 no-padding item_lot_large" style="position: relative">
            @if( $retirado)
                <div class="">

                </div>
            @elseif($fact_devuelta)
                 <div class="" style="font-size: 10px">

                </div>
            @elseif($awarded && $cerrado &&  ($vendido || ($sub_historica && !empty($item->impadj_asigl0))) )
                <div class="retired">
                    {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                </div>
            @endif
            <div class="col-xs-12 col-sm-5 col-lg-4 no-padding">
                <div class="border_img_lot">
                    <div class="item_img">
                        <div data-loader="loaderDetacados" class='text-input__loading--line'></div>
                        <img class="img-responsive lazy" style="display: none" data-src="{{$img}}" alt="{{$titulo}}">
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-7 col-lg-8">
                <div class="data-container">

                        <div class="title_item">
                            <span class="seo_h4">
                            {!! $titulo !!}
                            </span>
                        </div>
                        <div class="desc_lot">
                            {!! $item->desc_hces1 !!}
                        </div>

                <div class="data-price">

                    @if($subasta_venta)
                            <p class="salida-title">
                                {{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}:<span>  {{$item->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                            </p>
                    @else
                        	<p class="salida-title" style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden'}}">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}:<span> {{$item->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>

                    @endif
                    @if( ($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)

                            <p class="salida-title extra-color-one">
                                {{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}:
                                <span class="{{$winner}}">  {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                            </p>



                    @elseif ($subasta_web && $subasta_abierta_O && !empty($item->open_price) && !$cerrado)

                            <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}:
                            <span class="{{$winner}}"> {{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>

                    @endif

					@if( !$retirado && !$fact_devuelta)

                    @if( $awarded || $devuelto)
                      <p class="salida">
                        @if($devuelto)
                        <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                        @elseif($cerrado && $remate &&  (!empty($precio_venta) ) || ($sub_historica && !empty($item->impadj_asigl0)) )
                            @if($sub_historica && !empty($item->impadj_asigl0))
                                @php($precio_venta = $item->impadj_asigl0)
                            @endif
                        <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}: <span class="pill">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                        @elseif($cerrado &&  $vendido)
                        <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                        @elseif($cerrado &&  !$vendido)
                        <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
                        @endif
                      </p>
                    @endif
                     @if($subasta_online && !$cerrado)

                        <span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
                    @endif

                @endif
                </div>
            </div>
         </div>
     </div>
    </a>
</div>
