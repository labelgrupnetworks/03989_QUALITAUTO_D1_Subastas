<div class="{{$class_square}} square">
        <div class="item_lot">
                <div class="border_img_lot">
                    <a title="{{ $titulo }}" <?= $url?> >
                        <div class="img_lot">
<img class="img-responsive lazy" data-src="/img/load/lote_medium/{{ $item->imagen }}" alt="{{$titulo}}">
                        </div>
                        @if( $item->retirado_asigl0 !='N')
                            <div class="retired ">
                                {{ trans(\Config::get('app.theme').'-app.lot.retired') }}
                            </div>
                        @elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
                             <div class="retired" style ="text-transform: lowercase;">
                                {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                            </div>
                        <?php /*@elseif(\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' && (!empty($precio_venta)) || (empty($precio_venta)) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0)) )  
                         * cambio realizado por que el cliente quiere que aparezcan los lotes como cerrados en el historico
                         */
                         /*
                          * 2 cambio, se decide que no aparezca en la foto
                        @elseif($item->cerrado_asigl0 == 'S')
                            <div class="retired" style ="text-transform: uppercase;">
                                
                            </div>*/?>
                        @endif
                    </a>
                </div>
            <div class="data-container">
                @if(!empty($titulo))
                    <div class="title_lot">
                        <a title="{{ $titulo }}" <?= $url?>  >                                   
                            <h4>{{ $titulo }}</h4>
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
                    @if( $item->retirado_asigl0 =='N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')

                        @if($item->tipo_sub != 'V')   
                            @if($item->tipo_sub == 'W' && !empty($item->formatted_imptas_asigl0) && !empty($item->formatted_imptash_asigl0))
                            <p class="salida" id="estimacion" style="margin-bottom: 15px;">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }} <span> {{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                            @else
                            <p class="salida" id="estimacion" style="margin-bottom: 15px;"></p>
                            @endif
                            
                            
                            <p class="salida" id="precioSalida">
                                @if($item->cerrado_asigl0 != 'S')                                
                                    {{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}  <span> {{$item->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span> 
                                @endif
                            </p>
                            

                        @else
                            <p class="salida" id="actualBid">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}
                                <span>
                                     {{$item->formatted_actual_bid}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                               </span>       
                            </p>
                        @endif                   

                        @if( ($item->tipo_sub== 'P' || $item->tipo_sub== 'O') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                            <p class="salida" id="maxPuja">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}<span>  {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                        @elseif ($item->tipo_sub== 'P' || $item->tipo_sub== 'O' && $item->cerrado_asigl0 == 'N')
                             <p class="salida" id="noBids">{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }} </p> 
                        @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'S' && $item->cerrado_asigl0 == 'N'  )
                            <p class="salida" id="pujaActual">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
                                 <span class="{{$winner}}">  {{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                                </span>
                            </p>
                            @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'S' && $item->cerrado_asigl0 != 'N'  )<?php //ponemos el espacio para que no descuadre ?>
                            <p class="salida"></p> 
                        @else
                            <p class="salida"></p>
                        @endif
                        
                        @if( \Config::get('app.awarded'))
                            <p class="salida" id="awarded">
                                @if($item->cerrado_asigl0 == 'D')
                                    {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                                @elseif($item->cerrado_asigl0 == 'S' && $item->remate_asigl0 =='S' && (!empty($precio_venta) ) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0))  )    
                                    @if($item->subc_sub == 'H' && !empty($item->impadj_asigl0))
                                        @php($precio_venta = $item->impadj_asigl0)
                                    @endif      
                                    {{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}: <span >{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                                @elseif($item->desadju_asigl0 =='S')
                                        {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                                @elseif($item->cerrado_asigl0 == 'S')
                                    <?php //cambio realizado por que el cliente quiere que aparezcan los lotes como cerrados en el historico?>
                                    {{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }} 
                                @endif
                            </p>
                        @endif
                        <div class="divider-lot"></div>
                        
                        
                        
                        
                        <div class="lot-btn-clock">
                    <div class="lot-btn"><a title="{{ $titulo}}" <?= $url ?> >{{ trans(\Config::get('app.theme').'-app.sheet_tr.view')}}</a></div>
                    @if(($item->tipo_sub == 'P' || $item->tipo_sub == 'O') && $item->cerrado_asigl0=='N')
                    <p class="salida" id="cuenta" style="margin-left: 12px;">
                                <i class="fa fa-clock-o"></i>
                                <span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
                            </p>
                        @elseif($item->tipo_sub == 'P' || $item->tipo_sub == 'O')
                            <p class="salida"> </p>
                        @endif
                </div>
                                           
                        
                        
                        
                        
                       
                    
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
</div>
