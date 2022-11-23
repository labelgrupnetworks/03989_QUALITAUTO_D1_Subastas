<div class="{{$class_square}} large_square">
     <div class="col-xs-12 item_lot_large">
        <div class="col-xs-12 col-sm-5 col-lg-4">
            <a title="{{ $title }}" <?= $url;?> >
                <div class="border_img_lot">
                    <div class="content_item_img">
                        <div class="img_lot">
                            <img class="img-responsive lazy" data-src="/img/thumbs/260/{{\Config::get("app.emp")}}/{{$item->num_hces1}}/{{ $item->imagen }}" >
                        </div>

                    </div>
                    @if(($item->cerrado_asigl0 == 'S' && $item->lic_hces1 == 'S' && $item->subc_sub == 'H') || $item->retirado_asigl0 !='N' || ($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R') || (\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' &&  !empty($precio_venta) ) )
                    <div class="no_dispo-band">
                            <div class="no_dispo"></div>
                            <p>{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                        </div>
                    @endif
                </div>
            </a>
        </div>
        <div class="col-xs-12 col-sm-7 col-lg-8">
            <div class="data-container">
                @if(!empty($titulo))
                    <div class="title_lot">
                        <a title=""  <?= $url;?> >
                            <h4><?= $titulo?></h4>
                        </a>
                     </div>
                @endif
                <div class="data-price">
                <?php //@if($item->retirado_asigl0 =='N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')    ?>
                    @if($item->tipo_sub != 'V')
                        @if($item->formatted_impsalhces_asigl0 == '0' && $item->cerrado_asigl0 == 'N' && $item->tipo_sub == 'W' )
                               <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.lot-price-consult') }}
                           @elseif( \Config::get('app.estimacion'))
                                <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }} <span > {{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span> </p>
                        @elseif( \Config::get('app.impsalhces_asigl0'))
                                <p class="salida" style="visibility: {{ $item->ocultarps_asigl0 != 'S' ? 'visible' : 'hidden'}}">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}  <span > {{$item->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span> </p>
                        @endif
                    @elseif($item->tipo_sub === 'V')
						@php
							$pvp = Tools::moneyFormat($item->impsalhces_asigl0 + $item->impsalhces_asigl0 * ($item->comlhces_asigl0 / 100) * 1.21, trans("$theme-app.subastas.euros"), 2);
						@endphp
						<p class="salida">PVP:<span> {{ $pvp }}</span></p>
                    @endif

                    @if( ($item->tipo_sub== 'P' || $item->tipo_sub== 'O') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                        <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}<span>  {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                    @elseif (($item->tipo_sub== 'P' || $item->tipo_sub== 'O' )&& empty($item->max_puja) && $item->cerrado_asigl0 == 'N')
                        <p class="salida"><?php /* no quieren texto trans(\Config::get('app.theme').'-app.lot_list.no_bids')*/ ?>  </p>
                    @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'S' && $item->cerrado_asigl0 == 'N'  )
                        <p class="salida">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
                            <span class="{{$winner}}"> {{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                    @endif

                    @if( \Config::get('app.awarded') || $item->cerrado_asigl0 == 'D')
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
                <?php //@endif    ?>
                </div>
            </div>
         </div>
     </div>
</div>
