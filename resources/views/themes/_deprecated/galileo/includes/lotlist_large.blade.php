<div class="{{$class_square}} large_square">
    <a title="{{ $titulo }}" class="lote-destacado-link secondary-color-text" <?= $url?> >        
        <div class="col-xs-12 no-padding item_lot_large" style="position: relative">
            @if( $item->retirado_asigl0 !='N')
                <div class="retired">
                    {{ trans(\Config::get('app.theme').'-app.lot.retired') }}
                </div>
            @elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
                 <div class="retired" style="font-size: 10px">
                    {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                </div>
            @elseif(  \Config::get('app.awarded') && $item->cerrado_asigl0 == 'S' &&  !empty($precio_venta) )
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
                    @if(!empty($titulo))
                        <div class="title_item">
                            <span class="seo_h4">{{ $titulo}}</span>              
                        </div>
                    @endif
                    <div class="desc_lot">                              
                        <?= $item->desc_hces1 ?>                               
                    </div>
                    
                <div class="data-price">
                @if($item->retirado_asigl0 =='N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')    
                    @if($item->tipo_sub != 'V')          
                        @if( \Config::get('app.estimacion'))
                            <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}<span> {{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>                        
            <!-- El cliente no desea que en los lotes que aparecen en “Histórico” conste el precio de salida-->
                            @elseif( \Config::get('app.impsalhces_asigl0') && $item->subc_sub !='H' )
                <!-- El cliente no quiere mostrar el precio de salida de los lotes vendido en las postventa -->
                            
                                @if($item->cerrado_asigl0 == 'S' &&  !empty($precio_venta) &&  $item->remate_asigl0 !='S')
                                    <p class="salida-title"></p>
                                    <p></p>
                                @else
                                    <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}<span> {{$item->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                        @endif
                         @endif
                    @elseif($item->tipo_sub == 'V')   
                        <p class="salida">
                            <p class="salida-title">
                                {{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}
                                {{$item->formatted_impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}
                            </p>
                        </p>
                    @endif
                    @if( ($item->tipo_sub== 'P' || $item->tipo_sub== 'O') && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                        <p class="salida">
                            <p class="salida-title">
                                {{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
                                <span>  {{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                            </p>
                        </p>
                    @elseif ($item->tipo_sub== 'P' || $item->tipo_sub== 'O' && empty($item->max_puja))
                        <p class="salida">
                            <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}</p>
                        </p>    
                    @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'S' && $item->cerrado_asigl0 == 'N'  )
                        <p class="salida">
                            <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}
                            <span class="{{$winner}}"> {{ \Tools::moneyFormat($item->open_price) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                        </p>
                    @endif

                  
                    @if( \Config::get('app.awarded') || $item->cerrado_asigl0 == 'D')
                      <p class="salida">
                        @if($item->cerrado_asigl0 == 'D')
                        <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                        @elseif($item->cerrado_asigl0 == 'S' && !empty($precio_venta) && $item->remate_asigl0 =='S' )    
                        <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}: <span class="pill">{{ \Tools::moneyFormat($precio_venta) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span></p>
                        @elseif($item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta) || $item->desadju_asigl0 =='S'))
                        <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                        <!-- El cliente no desea que en los lotes que aparecen en “Histórico” conste la etiqueta por defecto de “No vendido”-->
                        @elseif($item->cerrado_asigl0 == 'S' &&  empty($precio_venta)  && $item->subc_sub !='H')
                        <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}</p>                       
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
    </a>
</div>