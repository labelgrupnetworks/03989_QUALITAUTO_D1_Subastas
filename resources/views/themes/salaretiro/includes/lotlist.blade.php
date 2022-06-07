	<div class="{{$class_square}} square">
        <div class="item_lot">
                <div class="border_img_lot">
                    <a title="{{ $title }}" <?= $url?> >
                        <div class="img_lot">
                            <img class="img-responsive lazy" data-src="/img/load/lote_medium/{{ $item->imagen }}" >

                        </div>


                    @if(($item->cerrado_asigl0 == 'S' && $item->lic_hces1 == 'S' && $item->subc_sub == 'H') || $item->retirado_asigl0 !='N' || ($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R') || (\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' &&  !empty($precio_venta) ) )
                    <div class="no_dispo-band">
                            <div class="no_dispo"></div>
                            <p>{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                        </div>
                    @endif
                    </a>
                </div>
            <div class="data-container">

                @if(!empty($titulo))
                    <div class="title_lot">
                        <a title="" <?= $url?>  >
                            <div class="control-title"><h4><?= $titulo?></h4></div>
                        </a>

                @endif
                </div>
                <div class="data-price">
                    <?php //@if( $item->retirado_asigl0 =='N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R') ?>

                        @if($item->tipo_sub != 'V')
                           @if($item->formatted_impsalhces_asigl0 == '0' && $item->cerrado_asigl0 == 'N' && $item->tipo_sub == 'W' )
                               <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.lot-price-consult') }}
                           @elseif( \Config::get('app.estimacion'))
                               <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }} <span> {{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                           @elseif( \Config::get('app.impsalhces_asigl0'))
                               <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}  <span> {{$item->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span> </p>
                           @endif
						@else
                            <p class="salida">{{ trans(\Config::get('app.theme').'-app.subastas.price_now') }}
                                <span>
                                     {{$item->formatted_actual_bid}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                               </span>
							</p>

							<p class="salida">{{ trans(\Config::get('app.theme').'-app.subastas.previous_price') }}
                                <s>
                                     {{$item->formatted_impsalweb_asigl0}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                               </s>
							</p>
                        @endif

                        @if( ($item->tipo_sub== 'P' || $item->tipo_sub== 'O') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                            <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}<span>  {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>

                        @elseif (($item->tipo_sub== 'P' || $item->tipo_sub== 'O') && $item->cerrado_asigl0 == 'N' && empty($item->max_puja))
						<p class="salida"><?php /* no quieren texto trans(\Config::get('app.theme').'-app.lot_list.no_bids')*/ ?> </p>

                        @elseif ($item->tipo_sub == 'W' && ($item->subabierta_sub == 'S' || $item->subabierta_sub == 'O') && $item->cerrado_asigl0 == 'N' && $item->open_price >= $item->impsalhces_asigl0)
                            <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
                                 <span class="{{$winner}}">  {{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                                </span>
                            </p>

                        @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'S' && $item->cerrado_asigl0 != 'N'  )<?php //ponemos el espacio para que no descuadre ?>
                            <p class="salida"></p>

                        @endif
                        @if( \Config::get('app.awarded'))
                            <p class="salida availability">
                                @if($item->cerrado_asigl0 == 'D')
                                    {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                                @elseif($item->cerrado_asigl0 == 'S' && !empty($precio_venta) && $item->remate_asigl0 =='S' )
                                    {{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}: <span class="pill">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                                @elseif($item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta)  || $item->desadju_asigl0 =='S'))
                                    {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                                @elseif(($item->cerrado_asigl0 == 'S' && $item->lic_hces1 == 'S' && $item->subc_sub == 'H') || $item->retirado_asigl0 !='N' || ($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R') || (\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' &&  !empty($precio_venta) ) )
                                        {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                                @elseif($item->cerrado_asigl0 == 'S' && empty($precio_venta) && $item->subc_sub == 'H')
                                    {{ trans(\Config::get('app.theme').'-app.subastas.consult_disp') }}
                                @elseif($item->cerrado_asigl0 == 'S' &&  empty($precio_venta))
                                    {{ trans(\Config::get('app.theme').'-app.lot_list.available') }}
                                @else
                                    {{ trans(\Config::get('app.theme').'-app.lot_list.available') }}
                                @endif
                            </p>
                        @endif
                        @if(($item->tipo_sub == 'P' || $item->tipo_sub == 'O') && $item->cerrado_asigl0=='N')
                            <p class="salida">
                                <i class="fa fa-clock-o"></i>
                                <span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
                            </p>
                        @elseif($item->tipo_sub == 'P' || $item->tipo_sub == 'O')
                            <p class="salida"></p>
                        @endif

                    <?php /* @else
                        @if($item->tipo_sub != 'V')

                           @if( \Config::get('app.estimacion'))
                               <p class="salida"><span></span></p>
                           @elseif( \Config::get('app.impsalhces_asigl0'))
                                <p class="salida"><span></span></p>
                           @endif

                        @else
                            <p class="salida"><span></span></p>
                        @endif
                        <?php // si son tipo P o O tienen dos lineas mas, la puja y el reloj ?>
                        @if( ($item->tipo_sub== 'P' || $item->tipo_sub== 'O') )
                            <p class="salida"></p>
                             <p class="salida"></p>
                        @endif
                        @if( \Config::get('app.awarded'))
                         <p class="salida"></p>
                        @endif


                    @endif*/ ?>

                </div>

            </div>

        </div>
</div>
<script>

    //--- Añadimos tres puntitos al final en firefox
    $('.square .title_lot h4 span:last-child').each(function(){
        var alto = parseInt($(this).css('height'))
        console.log(alto)
        if(alto > 28){
           $(this).addClass('truncat')
        }
    })

    //-----
</script>
