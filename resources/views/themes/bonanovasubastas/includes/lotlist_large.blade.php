<div class="{{$class_square}} large_square">
     <div class="col-xs-12 item_lot_large">
        <div class="col-xs-12 col-sm-5 col-lg-4">
            <a title="{{ $titulo }}" <?= $url;?> >
                <div class="border_img_lot">
                    <div class="content_item_img">
                        <div class="img_lot">
                            <img class="img-responsive lazy" data-src="{{Tools::url_img('lote_medium',$item->num_hces1,$item->lin_hces1)}}" alt="{{$titulo}}">
                        </div>
                        @if( $item->retirado_asigl0 !='N')
                        <div class="retired" style="top:5px; right:5px">
                            {{ trans(\Config::get('app.theme').'-app.lot.retired') }}
                        </div>
                        @elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
                             <div class="retired" style ="top:5px; right:5px">
                                {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                            </div>
        @elseif(\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta) || $item->desadju_asigl0 =='S' || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0))) )
                            <div class="retired" style ="background:#2b373a;top:5px; right:5px">
                                {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xs-12 col-sm-7 col-lg-8">
            <div class="data-container">
                @if(!empty($titulo))
                    <div class="title_lot">
                        <a title="{!! $titulo !!}"  <?= $url;?> >
                            <h4>{!! $titulo !!}</h4>
                        </a>
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
                @if($item->retirado_asigl0 =='N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')
                    @if($item->tipo_sub != 'V')
						@if( \Config::get('app.estimacion'))
								<p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }} <span > {{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span> </p>
						@elseif( \Config::get('app.impsalhces_asigl0'))
								<p class="salida" style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden'}}">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}  <span > {{$precio_salida}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span> </p>
						@endif
                    @elseif($item->tipo_sub == 'V')
                        <p class="salida">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}:
                        <span >
                            {{$item->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                        </p>
                    @endif
                    @if( ($item->tipo_sub== 'P' || $item->tipo_sub== 'O') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                        <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}<span>  {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                    @elseif ($item->tipo_sub== 'P' || $item->tipo_sub== 'O' && empty($item->max_puja) && $item->cerrado_asigl0 == 'N')
                        <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </p>
                    @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 == 'N' && $item->open_price != 0 )
                        <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
                             <span class="{{$winner}}">  {{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                            </span>
                        </p>
                     @elseif($item->tipo_sub == 'W' && $item->subabierta_sub == 'O' && $item->cerrado_asigl0 == 'N' && $item->open_price == 0)
                         <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </p>
                    @endif


                    @if( \Config::get('app.awarded') || $item->cerrado_asigl0 == 'D')
                      <p class="salida">
                        @if($item->cerrado_asigl0 == 'D')
                            {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                         @elseif($item->cerrado_asigl0 == 'S' && $item->remate_asigl0 =='S' &&  (!empty($precio_venta) ) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0)) )
                                @if($item->subc_sub == 'H' && !empty($item->impadj_asigl0))
                                    @php($precio_venta = $item->impadj_asigl0)
                                @endif
                            {{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}: <span class="pill">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                        @elseif($item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta) || $item->desadju_asigl0 =='S'))
                            {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                        @elseif($item->cerrado_asigl0 == 'S' &&  empty($precio_venta))
                            {{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}
                        @endif
                      </p>
                    @endif
                    @if(($item->tipo_sub == 'P' || $item->tipo_sub == 'O') && $item->cerrado_asigl0=='N')

                        <span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
                    @endif
                    @if(!empty($data['sub_data']) && !empty($data['sub_data']->opcioncar_sub) && $data['sub_data']->opcioncar_sub == 'S' && $item->tipo_sub == 'W' && $item->cerrado_asigl0=='N' && $item->fac_hces1=='N' &&  strtotime("now") > strtotime($item->orders_start)  &&   strtotime("now") < strtotime($item->orders_end))
                        <div class="input-group">
                                <input placeholder="" class="form-control input-lg" value="{{$item->impsalhces_asigl0}}" type="text">
                                <div class="input-group-btn">
                                    <button data-from="modal" type="button" class="lotlist-orden btn btn-lg btn-custom" ref="{{$item->ref_asigl0}}">{{ trans(\Config::get('app.theme').'-app.lot.pujar') }}</button>
                                </div>
                        </div>
                    @endif
                @endif
                </div>
            </div>
         </div>
     </div>
</div>
