<div class="{{$class_square}} lot-large large_square no-padding hidden">
    <div class="lot-large-wrapper flex">

        @if(!empty($item->oferta_asigl0) && ($item->oferta_asigl0 == 1 || $item->oferta_asigl0 == 2))
            @if($item->oferta_asigl0 == 2)
                @php($class_color = 'hot-sale')
            @else
                <?php
                $porcentaje = 0;
                if($item->imptas_asigl0>0){
                    $porcentaje = round(((($item->imptas_asigl0 -  $item->impsalhces_asigl0)/$item->imptas_asigl0) *100), 0);
                }
                $porcentaje = round(((($item->imptas_asigl0 -  $item->impsalhces_asigl0)/$item->imptas_asigl0) *100), 0);
                if( $porcentaje> 19){
                    $class_color = 'd-50';
                }elseif($porcentaje<= 19 && $porcentaje> 0){
                    $class_color = 'd-10';
                }else{
                    $class_color = 'd-20';
                }

                ?>
            @endif
            <div class="discount {{$class_color}}">
                @if($item->oferta_asigl0 == 1)
                    <div>{{$porcentaje}}%</div>
                    <div>{{ trans($theme.'-app.lot_list.discount') }}</div>
                @elseif($item->oferta_asigl0 == 2)
                    <div>{{ trans($theme.'-app.lot_list.gran') }}</div>
                    <div>{{ trans($theme.'-app.lot_list.hot_sale') }}</div>
                @endif
            </div>
        @endif

        <a title="{{ $titulo }}" <?= ($item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')?$url:'';?> ></a>
        @if( Config::get('app.icon_multiple_images')&& count($item->imagenes) > 1)
        <div class="more-picture" data-toggle="tooltip" title="{{ trans($theme.'-app.lot_list.more_pictures') }}">
            <span>{{ trans($theme.'-app.lot_list.more_pictures') }}</span>
            <img src="/themes/{{$theme}}/assets/img/more-picture.png">
        </div>
        @endif

        @if( Config::get('app.icon_multiple_images') && count($item->videos) > 0)
                <span class="more-picture" href="#" data-toggle="tooltip" title="{{ trans($theme.'-app.lot_list.more_videos') }}">
                <span>{{ trans($theme.'-app.lot_list.more_videos') }}</span>
                <img src="/themes/{{$theme}}/assets/img/more-video.png" />
            </span>
        @endif


        @if(!empty($item->descdet_hces1))
        <div class="cont hidden" ><?= $item->descdet_hces1 ?></div>
            <div onload="myFunction()" class="hist hidden">
                <img width="35px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFcAAABbCAYAAAAGChkXAAAACXBIWXMAAAsSAAALEgHS3X78AAAD+ElEQVR4Ae2d3W3bMBCAr0Xf3b71Ld7A3qAOoAE8gq0F6gkEQxPYCygewU95EmB7gALxBs5b3xpP4ILotbCDmjz+nEhJ9wFCECCRyQ8XiqSOlw+XywUEHj6KVz5ELiMilxGRy4jIZUTkMvKpLQ2tq2IMAJ8B4C3Ly5cEmmQkmXluXRVKnBI4wa/q+2+EXz0o4QCghO/V1ywv3xpospGocjEap3iNAt76CABbdcWM8sblYoQqmYvAQu+hRK9QdKMR3ZhclLrAa9DIh95yRsmrpiQ3IreuimVEqe85o+Al9wexyq2rQj2cNgDwwPYh7rwCwCzLyz3XB7DIxSFARcb34DcPz1q1lWOoCC4XZwAqWpt4WIXiiFEcdGYRVC4OA9tExlZb1Fg8DTlMBFv+1lUxA4BdS8UCtnuH/QhCELnYoKdQjYrMUyjB3nI7JvYvQQR7jbk4xu58G5Ewjz5jsHPk4qxg2zJZtmyxn044ycV57KbFDy8qqn8b7K81rpG7bNk81ocR9tca6zHXZ5zN8pL0c79+nuDHcxX9vu+wHn9dInfj0rIOYN1vK7m4u5XiJkwTPGD/yZDlXu3H9pmFzcPNJnJT2Y+NycAmwEhyJWpvIEcvNXKnErX/GKAPI1S5ErW3kHwY5dZVMezRgoHKCL1ooUQu6U+ghxi9UOQG2zzuGEYvWrn4VJQh4f+MTLMGU+Q6b7f1BK0fk9xJ3+0Z0PqRyPVD68eUn+u0SezLl69D8jZiZLzGXEp+bJ/R+pG0fUZELiMil5G7cilrZ0HPXblZXp7EnR8yLDAichkRuYyYVmiHGAsJrqQQBg66W5oiN4mTiAmj9WOS24ozthHR+jHJZTtG1BG0fiRyPTAl5mnl4tks7aDdY4xeKFOxrmePu2L0InLd8ZeLewzHuP1IjiNl74W6Qlt1y403JB8kuVlebvD4pgBwRh9h5CISvX8ge7CV2/foPbPIxTkve3WNxLGqy2C15Zjl5QorbPSRV+w/GZf93L5mPVr321ourqfXtr/XctYuB6ydT63XVfHSk/RStWBwypnzec0z7cHs4eyTWe8sF5d/XU8xnfikGHi9oMRqRnOfeyTM3Ldak/fbX1wKdk3wnLrE1RHk1XrHBAcRCwx1xcb4XqmtdcUmIQu3BU0KwYaNW7j/q9o7Troi3jV1VazaUssxy0uW47ds6UzY4MeEo/iIpVXYzjU3VT93gTtqqdTPXdpuwrgglZ8ZiVIQHkv5zRpK8lP5BZtQ0ysbYlfbH15V2w8p+nBVbT9ahnxS/w8Na5apa4jX2DCEnDHl6oTXnrNMti3yz+YYkcxyRkQuIyKXEZHLiMhlROQyInIZEbmMiFwuAOA3AiJgLAOs1GUAAAAASUVORK5CYII=")/>
            </div>
            @endif
            @if(!empty($item->contextra_hces1))
            <div class="more-360">
                <div class="sobreimg">{{ trans($theme.'-app.home.hover360') }}</div>
                <img width="35px" src="data:image/png;base64,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"/>
            </div>
            @endif
            @if($item->destacado_asigl0 == 'S')
            <div class="destacado" role="button">
                <img class="img-responsive" src="/themes/{{$theme}}/assets/img/destacado.png"  alt="{{(\Config::get( 'app.name' ))}}" style="width:85px; margin-bottom: 20px; position:relative"/>

            </div>
            @endif
        <div class="lot-large-block-img flex">
            <a title="{{ $titulo }}" <?= $url;?>  class="lot-large-img flex valign">
                <img class="img-responsive lazy" data-src="{{Tools::url_img('lote_medium',$item->num_hces1,$item->lin_hces1)}}" alt="{{ $titulo }}">
            </a>
        </div>
        <div class="lot-large-block-content">
            <a title="{{ $titulo }}" <?= $url;?> class="lot-title">
                <span>{{ trans($theme.'-app.lot.lot-name') }} {{ $item->ref_asigl0 }}</span>
            </a>
                @if( ( \Config::get( 'app.descweb_hces1' ) ) ||  ( \Config::get( 'app.desc_hces1' )))
                    <div class="desc_lot data-desc">
                        @if( \Config::get('app.descweb_hces1'))
                            <p><?= $item->descweb_hces1 ?></p>
                        @elseif ( \Config::get('app.desc_hces1' ))
                            <p><?= $item->desc_hces1 ?></p>
                        @endif
                    </div>
                @endif
                <div class="group-pujar flex valign">
                    @if($item->retirado_asigl0 =='N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')
                        <div class="data-content">
                        @if($item->tipo_sub != 'V')

                                <div class="price-content">
                                    @if( \Config::get('app.estimacion'))
                                        <p class="puja">{{ trans($theme.'-app.lot.estimate') }}</p>
                                        <p class="price">{{$item->formatted_imptas_asigl0}} -  {{$item->formatted_imptash_asigl0}} {{ trans($theme.'-app.subastas.euros') }}</p>
                                    @elseif( \Config::get('app.impsalhces_asigl0'))
                                        <p class="puja">{{ trans($theme.'-app.lot.lot-price')}}</p>
                                        <p class="price">{{$item->formatted_impsalhces_asigl0}} {{ trans($theme.'-app.subastas.euros') }}</p>
                                    @endif
                                </div>

                        @else
                            <div class="price-content  ">
                                @if(!empty($item->oferta_asigl0) && ($item->oferta_asigl0 == 1 || $item->oferta_asigl0 == 2))
                                    <p class="puja">{{ trans($theme.'-app.subastas.price_salida_venta') }}</p>
                                @else
                                    <p class="puja">{{ trans($theme.'-app.subastas.precio_estimado') }}</p>
                                @endif
                                <p class="price  gold <?=!empty($item->oferta_asigl0) && $item->oferta_asigl0 == 1?'tachado':'';?>">{{$item->formatted_imptas_asigl0}}  {{ trans($theme.'-app.subastas.euros') }}</p>
                            </div>
                            <div class="price-content">
                            @if(!empty($item->oferta_asigl0) && ($item->oferta_asigl0 == 1 || $item->oferta_asigl0 == 2))
                                <p class="puja">{{ trans($theme.'-app.subastas.nuestro_precio') }}</p>
                                @else
                                <p class="puja">{{ trans($theme.'-app.subastas.price_sale') }}</p>
                                @endif
                                <p class="price gold">{{$item->formatted_actual_bid}}  {{ trans($theme.'-app.subastas.euros') }}</p>
                            </div>
                        @endif
                        <?php
                            $sub_ref= $item->cod_sub.'-'.$item->ref_asigl0;
                        ?>
                        @if( ($item->tipo_sub== 'P' || $item->tipo_sub== 'O' || ($item->tipo_sub == 'W' && $item->subabierta_sub == 'P')) && $item->cerrado_asigl0 == 'N' && !empty($item->max_puja))
                                <div class="price-content">
                                    <p class="puja">{{ trans($theme.'-app.lot.puja_actual') }}</p>
                                    <p class="{{$sub_ref}} {{$winner}} price">{{ \Tools::moneyFormat($item->max_puja->imp_asigl1) }} {{ trans($theme.'-app.subastas.euros') }}</p>
                                </div>
                        @elseif ($item->tipo_sub== 'P' || $item->tipo_sub== 'O' || ($item->tipo_sub == 'W' && $item->subabierta_sub == 'P') && empty($item->max_puja))
                               <div class="price-content remove-bid-tr-{{$sub_ref}}">
                                   <p class="puja {{$sub_ref}}">
                                    @if($item->cerrado_asigl0 == 'N')
                                        {{ trans($theme.'-app.lot_list.no_bids') }}
                                    @endif
                                   </p>
                              </div>
                              <div class="price-content update-bid-tr-{{$sub_ref}} hidden">
                                  <p class="puja">{{ trans($theme.'-app.lot.puja_actual') }}</p>
                                  <p class="{{$sub_ref}} {{$winner}} price"> </p>
                              </div>
                         <?php /* 2018_04_10 lo quito por que la W abierta funcionarÃ¡ como la O
                        @elseif ($item->tipo_sub == 'W' && $item->subabierta_sub == 'P' && $item->cerrado_asigl0 == 'N'  )
                                <div class="price-content">
                                    <p class="puja">{{ trans($theme.'-app.lot.puja_actual') }}
                                    <p class="price">{{ \Tools::moneyFormat($item->open_price) }} {{ trans($theme.'-app.subastas.euros') }}</p>
                                </div>
                          *
                          */
                         ?>
                        @endif
                        </div>
                        @if( \Config::get('app.awarded'))
                            <div class="btn-pujar">
                                    @if($item->cerrado_asigl0 == 'S' && (!empty($precio_venta) ||$item->desadju_asigl0 =='S') && $item->tipo_sub == 'V' )
                                       <a class="btn btn-custom" style="background: #bc9c7e;color: white; border-radius: 0" <?= $url?>>{{ trans($theme.'-app.subastas.sold_lot') }}</a>
                                    @elseif($item->cerrado_asigl0 == 'S' && $item->remate_asigl0 =='S' &&  (!empty($precio_venta) ) || ($item->subc_sub == 'H' && !empty($item->impadj_asigl0)) )
                                        @if($item->subc_sub == 'H' && !empty($item->impadj_asigl0))
                                            @php($precio_venta = $item->impadj_asigl0)
                                        @endif
                                        <a class="btn btn-custom buy-to" <?= $url?>>{{ trans($theme.'-app.subastas.buy_to') }}: <span>{{ \Tools::moneyFormat($precio_venta) }} {{ trans($theme.'-app.subastas.euros') }}</span></a>
                                    @elseif($item->cerrado_asigl0 == 'S' &&  (!empty($precio_venta) ||$item->desadju_asigl0 =='S'))
                                        <a class="btn btn-custom" <?= $url?>>{{ trans($theme.'-app.subastas.buy') }}</a>
                                     @elseif($item->cerrado_asigl0 == 'S' &&  empty($precio_venta) && $item->compra_asigl0 == 'N')
                                        <a class="btn btn-custom " <?= $url?>> {{ trans($theme.'-app.subastas.dont_available') }}</a>
                                    @elseif($item->cerrado_asigl0 == 'S' &&  empty($precio_venta))
                                        <a class="btn btn-custom" <?= $url?>>{{ trans($theme.'-app.subastas.dont_buy') }} <i class="fa fa-shopping-cart"></i></a>
                                    @elseif(!empty($data['sub_data']) && $data['sub_data']->tipo_sub =='W' && strtotime($data['sub_data']->end) > time() && strtotime($data['sub_data']->start) < time() && $subasta_finalizada == false)
                                        <?php
                                            //en caso de que este el tiempo real pujando en ese momento, activamos un texto que le
                                            //avisa al cliente y lo dirige a pujar en vivo.
                                            $url_tiempo_real=\Routing::translateSeo('api/subasta').$data['sub_data']->cod_sub."-".str_slug($data['sub_data']->name)."-".$data['sub_data']->id_auc_sessions;
                                        ?>
                                        <a href="{{ $url_tiempo_real }}" target="_blank" class="btn btn-custom">{{ trans($theme.'-app.subastas.bid_online_now') }}</a>
                                    @else
                                        <a class="btn btn-custom" <?= $url?>>{{ trans($theme.'-app.lot.pujar') }} <i class="fa fa-hand-paper-o"></i></a>
                                    @endif

                            </div>
                        @endif
                    @else
                    <div class="price-content"></div>
                        @if( $item->retirado_asigl0 == 'S')
                        <div class="btn-pujar">
                            <a class="btn btn-custom">
                                {{ trans($theme.'-app.lot.retired') }}
                            </a>
                        </div>
                        @elseif($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R')
                        <div class="btn-pujar">
                            <a class="btn btn-custom">
                                 {{ trans($theme.'-app.subastas.dont_available') }}
                            </a>
                        </div>
                        @endif
                    @endif
                </div>
                @if($item->retirado_asigl0 =='N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R')
                    @if(($item->tipo_sub == 'P' || $item->tipo_sub == 'O') && $item->cerrado_asigl0=='N')
                        <div class="timeLeft">
                            <span data-countdown="{{strtotime($item->close_at) - getdate()[0] }}" data-format="<?= \Tools::down_timer($item->close_at); ?>" class="timer"></span>
                            <i class="far fa-clock"></i>
                            <!--<span class="clock"></span>-->
                        </div>
                    @endif
                    @if(!empty($data['sub_data']) && !empty($data['sub_data']->opcioncar_sub) && $data['sub_data']->opcioncar_sub == 'S' && $item->tipo_sub == 'W' && $item->cerrado_asigl0=='N' && $item->fac_hces1=='N' &&  strtotime("now") > strtotime($item->orders_start)  &&   strtotime("now") < strtotime($item->orders_end))
                        <div class="direct-bid">
                            <input placeholder="" class="form-control" value="{{$item->impsalhces_asigl0}}" type="text">
                            <button data-from="modal" type="button" class="lotlist-orden btn-color" ref="{{$item->ref_asigl0}}">{{ trans($theme.'-app.lot.pujar') }}</button>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>






<script>

function myFunction() {
    alert()
}

 $('.cont').ready(function(){
        if($('.cont p span').text().length > 10){
            $(this).siblings().removeClass('hidden')
        }
    })

</script>
