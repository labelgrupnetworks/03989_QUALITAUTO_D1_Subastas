
<?php

    $a = explode("-",$item->descweb_hces1);
    $titulo = $item->ref_asigl0." - ".$a[0];
    unset($a[0]);
    $descweb_hces1 = implode("-",$a);
    $compra = $item->compra_asigl0 == 'S'? true : false;
    $retirado = $item->retirado_asigl0 !='N'? true : false;
    $desadjudicado = $item->desadju_asigl0 =='S'? true : false;
?>

<div class="col-xs-12 col-sm-6 col-lg-4 square">
    <a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?> >
        @if( $retirado)
            <div class="retired ">
                {{ trans(\Config::get('app.theme').'-app.lot.retired') }}
            </div>
        @elseif($fact_devuelta)
            <div class="retired" style="font-size: 10px">
                {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
            </div>
        @elseif($awarded && $cerrado &&  (!empty($precio_venta)) || ($sub_historica && !empty($item->impadj_asigl0)) )
            <div class="retired">
                {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
            </div>
        @endif
        <div class="item_home lotlist">    
            <div class="item_img">
                <div data-loader="loaderDetacados" class='text-input__loading--line'></div>  
                <div class="degradado"></div>                    
                <img class="img-responsive lazy" style="display: none;" data-src="{{$img}}" alt="{{$titulo}}">
                @if (isset($descweb_hces1))
                    <span>{{ $descweb_hces1 }}</span>
                @endif
            </div>

            <div class="data-container">             
                
                <div class="title_item">
                    <span class="seo_h4" style="text-align: center;">{!! $titulo !!}</span>              
                </div>                
                 
                 <div class="desc_lot"></div>
            
                <div class="data-price text-center">
                @if( !$retirado && !$fact_devuelta)
                    <div class="col-xs-6 text-center">
                        @if($subasta_venta) 
                            <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
                            <span class="salida-title letter-price-salida">{{$item->formatted_actual_bid}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                            <div class="js-divisa" value="{{$item->actual_bid}}"></div>
                        @else                        
                            <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                            <span class="salida-title letter-price-salida">{{$item->formatted_impsalhces_asigl0}} 
                            {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                            <div class="js-divisa" value="{{ $item->impsalhces_asigl0 }}"></div>
                                   
                        @endif                   
                    </div>
                    <div class="col-xs-6 text-center">
                        @if(($subasta_online || ($subasta_web && $subasta_abierta_P)) && !$cerrado && $hay_pujas)
                            <p class="salida-title2">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                            <span class="salida-title letter-price-salida {{$winner}}">
                                    {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} 
                                    {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                            </span>
                            <div class="js-divisa" value="{{ $item->max_puja->imp_asigl1 }}"></div>
                                                            
                        @elseif ($subasta_web && $subasta_abierta_O && !empty($item->open_price) && !$cerrado  )              
                                <p class="salida-title2">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                                <span class="salida-title letter-price-salida {{$winner}}">
                                {{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>                    
                                <div class="js-divisa" value="{{ $item->open_price }}"></div>
                        @endif
                    </div>
                    <div class="col-xs-6 text-center">
                        
                    @if( $awarded || $devuelto)

                            @if($devuelto)
                                <p class="salida-title2">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                                <div class="salida-price"></div>
                            @elseif($cerrado && $remate &&  (!empty($precio_venta) ) || ($sub_historica && !empty($item->impadj_asigl0)) )
                                @if($sub_historica && !empty($item->impadj_asigl0))
                                    @php($precio_venta = $item->impadj_asigl0)
                                @endif

                                <p class="salida-title2">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</p>
                                <span class="salida-title letter-price-salida">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                                <div class="js-divisa" value="{{ $precio_venta }}"></div>  
                            @elseif($cerrado &&  !empty($precio_venta) &&  !$remate)
                                <p class="salida-title2">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                                <div class="salida-price"></div>
                            @elseif($cerrado && empty($precio_venta))
                                <p class="salida-title2">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>
                                <div class="salida-price"></div>
                            @endif
                    @endif
                    </div>
                    
               
                @endif

                </div>

                @if($subasta_online && !$cerrado && !$retirado && !$desadjudicado)
                    <p class="salida-time">
                        {{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}
                        <span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
                    </p>       
                @elseif($cerrado && empty($precio_venta) && $compra && !$retirado && !$desadjudicado)
                    <div class="salida-time" style="text-align:center;display:block;padding-top:10px;">
                        {{ strtoupper(trans(\Config::get('app.theme').'-app.subastas.buy_lot')) }}
                    </div>
                @endif
                
            </div>
               
        </div>
    </a>
</div>
