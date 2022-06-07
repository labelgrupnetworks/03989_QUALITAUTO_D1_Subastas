
 <?php
    $url = "";
    //Si no esta retirado tendrá enlaces
    if($bann->retirado_asigl0 =='N'){
        $url_friendly = str_slug($bann->webfriend_hces1);
        $url_friendly = \Routing::translateSeo('lote').$bann->sub_asigl0."-".str_slug($bann->name).'-'.$bann->id_auc_sessions."/".$bann->ref_asigl0.'-'.$bann->num_hces1.'-'.$url_friendly;
        $url = "href='$url_friendly'";

    }
    $titulo ="";
    if(\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
        $titulo ="$bann->ref_asigl0  -  $bann->titulo_hces1";
    }elseif(!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
        $titulo = $bann->titulo_hces1;
    }elseif(\Config::get('app.ref_asigl0')){
        $titulo = trans(\Config::get('app.theme').'-app.lot.lot-name') ." ".$bann->ref_asigl0 ;
    }
?>

<div class="item" data-ref="{{$bann->ref_asigl0}}">

    <div class="lote">
        @if($bann->tipo_sub == 'V' && !empty($bann->oferta_asigl0) && ($bann->oferta_asigl0 == 1 || $bann->oferta_asigl0 == 2))
            @if($bann->oferta_asigl0 == 2)
                @php($class_color = 'hot-sale')@endphp
            @else
                <?php
                $porcentaje = 0;
                if($bann->imptas_asigl0>0){
                    $porcentaje = round(((($bann->imptas_asigl0 -  $bann->impsalhces_asigl0)/$bann->imptas_asigl0) *100), 0);
                }
                if( $porcentaje> 25){
                    $class_color = 'd-50';
                }elseif($porcentaje<= 25 && $porcentaje> 10){
                    $class_color = 'd-20';
                }else{
                    $class_color = 'd-10';
                }

                ?>
            @endif
            <div class="discount {{$class_color ?? 'hot-sale'}}">
                @if($bann->oferta_asigl0 == 1)
                    <div>{{$porcentaje}}%</div>
                    <div>{{ trans(\Config::get('app.theme').'-app.lot_list.discount') }}</div>
                @elseif($bann->oferta_asigl0 == 2)
                    <div>{{ trans(\Config::get('app.theme').'-app.lot_list.gran') }}</div>
                    <div>{{ trans(\Config::get('app.theme').'-app.lot_list.hot_sale') }}</div>
                @endif
            </div>
        @endif

        <div class="lote-recomendado">
 @if(!empty($bann->contextra_hces1))
            <div class="more-360" style="right:7px !important; background: transparent !important">
                <div class="sobreimg">{{ trans(\Config::get('app.theme').'-app.home.hover360') }}</div>
                <img style="width: 35px !important" width="35px" src="data:image/png;base64,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"/>
            </div>
            @endif

            <div class="lot-title">
                <a title="{{$titulo}}" <?= $url ?> >
                    <span>{{\trans(\Config::get('app.theme').'-app.lot.lot-name')}} {{ $bann->ref_asigl0 }}</span>
                </a>
            </div>



            <div class="lot-img">
                <a title="{{$titulo}}" <?= $url ?> >
                    <img src="{{Tools::url_img('lote_medium',$bann->num_hces1,$bann->lin_hces1)}}" alt="{{ $titulo}}">
                </a>
            </div>


            <div class="data-content">
                @if( $bann->retirado_asigl0 =='N')
                        @if($bann->tipo_sub == 'V')
                        <div class="price-content">
                            @if(!empty($bann->oferta_asigl0) && $bann->oferta_asigl0 == 1)
                                <p class="puja">{{ trans(\Config::get('app.theme').'-app.subastas.price_salida') }}</p>
                            @elseif((empty($bann->oferta_asigl0)) ||( !empty($bann->oferta_asigl0) && $bann->oferta_asigl0 == 2))
                                <p class="puja">{{ trans(\Config::get('app.theme').'-app.subastas.precio_estimado') }}</p>
                            @endif
                                <p class="price <?=!empty($bann->oferta_asigl0) && $bann->oferta_asigl0 == 1?'tachado':'';?> gold">{{ $bann->imptas_asigl0 }}  {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                        </div>
                       @endif
                                <div class="price-content">
                            @if( \Config::get('app.estimacion') || \Config::get('app.impsalhces_asigl0'))
                                @if( \Config::get('app.estimacion'))
                                    <p class="puja">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}</p>
                                    <p class="price">{{$bann->imptas_asigl0}} -  {{$bann->imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                                @elseif( \Config::get('app.impsalhces_asigl0'))

                                    @if($bann->tipo_sub == 'V' && !empty($bann->oferta_asigl0) && ($bann->oferta_asigl0 == 1 || $bann->oferta_asigl0 == 2))
                                        <p class="puja">{{ trans(\Config::get('app.theme').'-app.subastas.nuestro_precio') }}</p>
                                    @elseif($bann->tipo_sub == 'V')
                                        <p class="puja">{{ trans(\Config::get('app.theme').'-app.subastas.price_sale') }}</p>
                                    @else
                                        <p class="puja">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>
                                    @endif
                                        <p class="price gold">{{ $bann->impsalhces_asigl0 }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</p>
                                @endif
                            @endif

                        </div>
                       @if($bann->tipo_sub != 'V')
                        <div class="price-content">
                                @if (($bann->tipo_sub == 'O' ||   $bann->tipo_sub == 'P' || $bann->tipo_sub == 'W') &&  $bann->max_puja > 0)
                                    <p class="puja">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p>
                                    <p class="price">{{ \Tools::moneyFormat($bann->max_puja)}} €</p>
                                @elseif($bann->tipo_sub == 'O' ||   $bann->tipo_sub == 'P')
                                <p class="puja">
                                    @if($bann->cerrado_asigl0 == 'S')
                                            {{ trans(\Config::get('app.theme').'-app.subastas.finalized') }}
                                    @else

                                    @endif
                                </p>
                                @else
                                    <p class="puja">{{ trans(\Config::get('app.theme').'-app.lot_list.no_bids') }}</p>
                                @endif
                        </div>
                       @endif

          </div>
                    @if( \Config::get('app.awarded') || $bann->cerrado_asigl0 == 'D')
                        <div class="btn-pujar">
                            @if($bann->cerrado_asigl0 == 'D')
                                <a class="btn btn-custom" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</a>
                            @elseif($bann->cerrado_asigl0 == 'S' && !empty($bann->max_puja) && $bann->tipo_sub == 'V' )
                                 <a class="btn btn-custom" style="background: #bc9c7e;color: white; border-radius: 0" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.subastas.sold_lot') }}</a>
                            @elseif($bann->cerrado_asigl0 == 'S' && !empty($bann->max_puja) && $bann->remate_asigl0 =='S' )
                                <a class="btn btn-custom buy-to" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}: <span >{{ \Tools::moneyFormat($bann->max_puja) }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</a>
                            @elseif($bann->cerrado_asigl0 == 'S' &&  !empty($bann->max_puja) &&  $bann->remate_asigl0 !='S')
                                <p class="btn btn-custom" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                            @elseif($bann->cerrado_asigl0 == 'S' &&  empty($bann->max_puja))
                                <a class="btn btn-custom" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }} <i class="fa fa-shopping-cart"></i></a>
                            @elseif($bann->tipo_sub == 'V')
                                <a class="btn btn-custom" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.subastas.buy_lot') }} <i class="fa fa-shopping-cart"></i></a>
                            @else
                                <a class="btn btn-custom" <?= $url?>>{{ trans(\Config::get('app.theme').'-app.lot.pujar') }} <i class="fa fa-hand-paper-o"></i></a>
                            @endif
                        </div>
                    @endif
                    <div class="timeLeft">
                    @if(($bann->tipo_sub == 'P' || $bann->tipo_sub == 'O') && $bann->cerrado_asigl0=='N')

                            <span
                                data-countdown="{{strtotime($bann->close_at) - getdate()[0] }}"

                                data-format="<?= \Tools::down_timer($bann->close_at); ?>"
                                class="timer">
                            </span>

                        @endif
                    </div>








                @endif




            </div>
        </div>
    </div>








