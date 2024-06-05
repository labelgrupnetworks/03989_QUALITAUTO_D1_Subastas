<div class="{{$class_square}} small_square">
     <div class="item_lot_mini col">
         <div class="content_item_mini">

                    <a title="{{ $titulo }}" <?= $url;?>  >
                        <div class="img_lot">
                            <img class="img-responsive lazy" data-src="{{Tools::url_img('lote_small',$item->num_hces1,$item->lin_hces1)}}" xoriginal="{{Tools::url_img('lote_medium_large',$item->num_hces1,$item->lin_hces1)}}" alt="{{$titulo}}">
                        </div>
                        @if( $item->retirado_asigl0 !='N')
							<div class="retired-border">
								<div class="retired">
									<span class="retired-text lang-{{ \Config::get('app.locale') }}">
										{{ trans(\Config::get('app.theme').'-app.lot.retired') }}
									</span>
								</div>
							</div>
                        @elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
							<div class="retired-border">
								<div class="retired">
									<span class="dont_available-text lang-{{ \Config::get('app.locale') }}">
										{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
									</span>
								</div>
							</div>
                        @elseif(\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' &&  !empty($precio_venta))
							<div class="retired-border">
								<div class="retired selled">
									<span class="retired-text lang-{{ \Config::get('app.locale') }}">
										{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
									</span>
								</div>
							</div>
                        @endif
                    </a>
                    <div class="title_lot text-center">
                    <a title="{{ $titulo }}" <?= $url;?>>
                       {{ $item->ref_asigl0 }}
                    </a>

                </div>
             <div class="text-center">
                @if( $item->retirado_asigl0 =='N')
                    @if( ($item->tipo_sub == 'P' || $item->tipo_sub== 'O' || $item->subabierta_sub == 'P') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                            <span class="{{$winner}}">  {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>

                    <?php //si no hay ordenes mostramos el precio de salida, ya que solo  ?>
                    @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 == 'N' && !empty($item->open_price) && $item->open_price > $item->impsalhces_asigl0 )

                            <span class="{{$winner}}">{{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                     <?php //en historico n odeben salir preic osañlida ?>
                    @elseif( \Config::get('app.impsalhces_asigl0') )
                           <span > </span>
                    @elseif( ($item->tipo_sub == 'V' || \Config::get('app.impsalhces_asigl0')) && $item->subc_sub !='H')
                        <span style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden'}}"> {{$precio_salida}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                    @elseif( \Config::get('app.estimacion'))
                            <span > {{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                    @endif
                @endif
             </div>
         </div>

        <div class="capaOculta " style="top: 200px;position: absolute; background: white;   border: 1px solid #ccc;display: none;z-index:999;">
            <img style="max-width:638px;max-height: 400px;" src="{{Tools::url_img('lote_medium_large',$item->num_hces1,$item->lin_hces1)}}">

        </div>
    </div>
</div>
