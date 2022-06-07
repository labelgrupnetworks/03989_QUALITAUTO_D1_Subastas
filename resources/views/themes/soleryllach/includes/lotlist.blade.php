<div class="{{$class_square}} square">
	{{--  he puesto aquí los iconos por que el video tiene enlace y si lo meto dentro del otro enlace el html genera un momton de elementos a href abriendolos y cerrandolos--}}
	@if(!empty($item->contextra_hces1))
		<div style="position:absolute;right: 45px; top:265px;z-index: 100;">
		<a href="{{$item->contextra_hces1}}" target="_blank">
			<img src="/default/img/icons/video.png" style="height:16px" />
		</a>
		</div>
	@endif
	@if( Config::get('app.icon_multiple_images') && !empty($item->imagenes)    && count($item->imagenes) > 1)
		<a title="{{ $titulo }}"  <?= $url;?> style='text-decoration: none;'>
			<div style="position:absolute;right: 30px; top:265px;z-index: 100;">
				<img src="/default/img/icons/camara.png" style="height:20px" />
			</div>
			<div style="position:absolute;right: 26px; top:257px;z-index: 100;">
				<img src="/default/img/icons/mas.png" style="height:8px" />
			</div>
		</a>

	@endif


    <a title="{{ $titulo }}"  <?= $url;?> style='text-decoration: none;'>

        <div class="item_lot">
                <div class="border_img_lot">
                        <div class="img_lot">
                            <img class="img-responsive lazy" data-src="{{Tools::url_img('lote_medium_large',$item->num_hces1,$item->lin_hces1)}}" alt="{{$titulo}}">

                        </div>
                        @if( $item->retirado_asigl0 !='N')
                            <div class="retired ">
                                {{ trans(\Config::get('app.theme').'-app.lot.retired') }}
                            </div>
                        @elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
                             <div class="retired" style ="background:#2b373a;text-transform: lowercase;">
                                {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                            </div>
                        @elseif(\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta) || $item->desadju_asigl0 =='S' || $item->subc_sub == 'H' && !empty($item->impadj_asigl0)) )
                            <div class="retired" style ="background:#2b373a;text-transform: lowercase;">
                                {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                            </div>
						@endif

                </div>
            <div class="data-container">
                @if(!empty($titulo))
                    <div class="title_lot">
                        <h4>{{ $titulo }}</h4>
                    </div>
                @endif
                @if( ( \Config::get( 'app.descweb_hces1' ) ) ||  ( \Config::get( 'app.desc_hces1' )))
                    <div class="desc_lot">
                            @if( \Config::get('app.descweb_hces1'))
                                <?= $item->descweb_hces1 ?>
                            @elseif ( \Config::get('app.desc_hces1' ))
                                <?= $item->desc_hces1 ?>
                            @endif
                    </div>
                @endif
                <div class="data-price">
                    @if( $item->retirado_asigl0 =='N')

                        @if($item->tipo_sub != 'V')

                           @if( \Config::get('app.estimacion'))
                               <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }} <span> {{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                           <?php //en historico n odeben salir preic osañlida ?>
                            @elseif( \Config::get('app.impsalhces_asigl0') && ($item->subc_sub !='H'  || !empty($precio_venta)))
                                <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}  <span> {{$precio_salida}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span> </p>
                            @else
                                <p class="salida"> <P>
                            @endif

                        @elseif($item->subc_sub !='H')
                            <p class="salida">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}
                                <span>
                                     {{$precio_salida}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                               </span>
                            </p>
                        @endif

                        @if( ($item->tipo_sub == 'P' || $item->tipo_sub== 'O' || $item->subabierta_sub == 'P') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                            <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}<span class="{{$winner}}">  {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>

                        @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 == 'N' && $item->open_price >= $item->impsalhces_asigl0  )
                            <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
                                 <span class="{{$winner}}">  {{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                                </span>
                            </p>
                         @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 != 'N'  )<?php //ponemos el espacio para que no descuadre ?>
                            <p class="salida"></p>
                         @else($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 != 'N'  )<?php //ponemos el espacio para que no descuadre ?>
                            <p class="salida"></p>
						@endif


						{{-- añado una linea más para las subastas W abiertas pujas, que se muestre el numero de licitadores y de pujas  --}}
						@if ($item->tipo_sub == 'W' &&  ($item->subc_sub == 'S' ||   $item->subc_sub == 'A') &&  ( $item->subabierta_sub == 'P' || $item->subabierta_sub == 'O'))
							<p class="salida" style="text-align:right">
								<img src="/default/img/icons/hammer.png" style="margin-right: 5px;" width="16px" height="16px">{{ $item->total_pujas }}
								<img src="/default/img/icons/licits.png" style="margin-left: 10px; margin-right: 5px;" width="16px" height="16px">{{ $item->total_postores }}
							</p>
						@endif


                        @if( \Config::get('app.awarded'))
                            <p class="salida">
                                @if($item->cerrado_asigl0 == 'D')
                                    {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                                @elseif($item->cerrado_asigl0 == 'S' && $item->remate_asigl0 =='S' &&  (!empty($precio_venta) ) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0)) )
									@if($item->subc_sub == 'H' && !empty($item->impadj_asigl0))
										@php($precio_venta = $item->impadj_asigl0)
									@endif
                                    {{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}: <span >{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                                @elseif($item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta) || $item->desadju_asigl0 =='S') )
								 {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                                @elseif($item->tipo_sub == 'W' && ($item->subc_sub == 'S' ||   $item->subc_sub == 'A')  && $item->compra_asigl0 == 'S' && $item->cerrado_asigl0 == 'S' && empty($precio_venta) )
								<button class="btn btn-primary" style="width: 100%;"> {{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }}</button>
                                @elseif($item->cerrado_asigl0 == 'S' &&  empty($precio_venta) &&  $item->subc_sub != 'H')
                                    {{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}
								@elseif($item->cerrado_asigl0=='N'  && $item->tipo_sub == 'W' &&  ($item->subc_sub == 'S' ||   $item->subc_sub == 'A') && $item->subabierta_sub == 'P')
								<button class="btn btn-primary" style="width: 100%;">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
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

                    @else
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



					@endif




                </div>

            </div>

        </div>
    </a>
</div>
