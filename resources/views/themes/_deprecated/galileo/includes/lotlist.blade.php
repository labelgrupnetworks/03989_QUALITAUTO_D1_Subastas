
<div class="{{$class_square}} square">
    
    <a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?> >
        @if( $item->retirado_asigl0 !='N')
            <div class="retired ">
                {{ trans(\Config::get('app.theme').'-app.lot.retired') }}
            </div>
        @elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
            <div class="retired" style="font-size: 10px">
                {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
            </div>
        @elseif(\Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' &&  !empty($precio_venta) )
            <div class="retired">
                {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
            </div>
        @endif
        <div class="item_lot">    
            <div class="item_img">
                <div data-loader="loaderDetacados" class='text-input__loading--line'></div>                      
                <img class="img-responsive lazy img-lot-list" style="display:  none" data-src="{{$img}}" alt="{{$titulo}}">
            </div>

            <div class="data-container">
                @if(!empty($titulo))
                    <div class="title_item">
                        <span class="seo_h4" style="text-align: center;"><?= $titulo ?></span>              
                    </div>
                 @endif
                  
                 <?php /* No habrá este apartado ya que el título ya contiene una descripcion
                 @if( ( \Config::get( 'app.descweb_hces1' ) ) ||  ( \Config::get( 'app.desc_hces1' )))
                 <div class="desc_lot">
                         @if( \Config::get('app.descweb_hces1'))
                             <?= $item->descweb_hces1 ?>
                         @elseif ( \Config::get('app.desc_hces1' ))
                             <?= $item->desc_hces1 ?>
                         @endif
                 </div>
                  
                 
                @endif
                  *  */
                 
                 ?>
                 <!-- El cliente no quiere mostrar informacio ndel lote en historico y mostraremos la descripcion”-->
                 
                 
            @if( $item->subc_sub =='H')  
            <div class="desc_lot">                              
                <?= $item->desc_hces1 ?>                               
            </div>
            @else
                  
            <div class="data-price text-center">
                @if( $item->retirado_asigl0 == 'N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')
                    @if($item->tipo_sub != 'V')      
                        @if( \Config::get('app.estimacion'))
                            <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}</p>
                            <p> {{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                       
                            @elseif( \Config::get('app.impsalhces_asigl0') )
                            
                <!-- El cliente no quiere mostrar el precio de salida de los lotes vendido en las postventa -->
                            
                                @if($item->cerrado_asigl0 == 'S' &&  !empty($precio_venta) &&  $item->remate_asigl0 !='S')
                                    <p class="salida-title"></p>
                                    <p></p>
                                @else
                                    <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                                    <p>{{$item->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                                @endif
                        @else
                            <p class="salida-title"></p>
                            <p class="salida-price"></p>
                        @endif
                    @else
                        <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
                            <p>
                                {{$item->formatted_actual_bid}}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                            </p>           
                    @endif                   
                    @if( ($item->tipo_sub == 'P' || $item->tipo_sub== 'O') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                                <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                                <p>{{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                    @elseif ($item->tipo_sub== 'P' || $item->tipo_sub== 'O')
                            <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}</p> 
                    @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'S' && $item->cerrado_asigl0 == 'N'  )              
                            <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                            <p class="{{$winner}}">{{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                    @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'S' && $item->cerrado_asigl0 != 'N'  )
                    
                    <?php //ponemos el espacio para que no descuadre ?>
                    <p class="salida-title extra-color-one"></p>
                            <p class="{{$winner}}"></p>
                    @endif

                        
                    @if( \Config::get('app.awarded'))
                            @if($item->cerrado_asigl0 == 'D')
                                <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                                <div class="salida-price"></div>
                            @elseif($item->cerrado_asigl0 == 'S' && !empty($precio_venta) && $item->remate_asigl0 =='S' )    
                                <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</p> <div class="salida-price">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                            @elseif($item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta) || $item->desadju_asigl0 =='S'))
                                <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                                <div class="salida-price"></div>
                             
                            @elseif($item->cerrado_asigl0 == 'S' &&  empty($precio_venta))
                                <p class="salida-title extra-color-one"></p>
                                <div class="salida-price"></div>
                            @else
                                <p class="salida-title extra-color-one"></p>
                                <div class="salida-price"></div>
                            @endif
                        @endif
                        @if(($item->tipo_sub == 'P' || $item->tipo_sub == 'O') && $item->cerrado_asigl0=='N')
                            <p class="salida-time">
                                <i class="fa fa-clock-o"></i>
                                <span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
                            </p>
                        @elseif($item->tipo_sub == 'P' || $item->tipo_sub == 'O')
                            <p class="salida-title extra-color-one"></p>
                            <div class="salida-price"></div>
                        @endif
                    @else
                        @if($item->tipo_sub != 'V')   
                       
                            @if( \Config::get('app.estimacion'))
                                <p class="salida-title extra-color-one"></p>
                                <div class="salida-price"></div>
                           @elseif( \Config::get('app.impsalhces_asigl0'))
                                <p class="salida-title extra-color-one"></p>
                                <div class="salida-price"></div>
                            @endif
                        @else
                            <p class="salida-title extra-color-one"></p>
                            <div class="salida-price"></div>
                        @endif
                        <?php // si son tipo P o O tienen dos lineas mas, la puja y el reloj ?>
                        @if( ($item->tipo_sub== 'P' || $item->tipo_sub== 'O') )
                            <p class="salida-title extra-color-one"></p>
                            <p class="salida-price"></p>
                    @endif
                        @if( \Config::get('app.awarded'))
                        <p class="salida-title extra-color-one"></p>
                        <p class="salida-price"></p>
                        
                        @endif
                    @endif

                </div>
              @endif  
             
            </div>
               
        </div>
    </a>
</div>
