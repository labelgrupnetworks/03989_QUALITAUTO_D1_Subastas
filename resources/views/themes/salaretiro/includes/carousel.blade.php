
 <?php
    $url = "";
    //Si no esta retirado tendrá enlaces
    if($bann->retirado_asigl0 =='N'){
        $url_friendly = str_slug($bann->webfriend_hces1);
        $url_friendly = \Routing::translateSeo('lote').$bann->sub_asigl0."-".str_slug($bann->name).'-'.$bann->id_auc_sessions."/".$bann->ref_asigl0.'-'.$bann->num_hces1.'-'.$url_friendly;
        $url = "href='$url_friendly'";
    }
    $title= $bann->titulo_hces1;
    $titulo ="";


    if(\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
        $bann->descweb_hces1 = str_replace ('<p>',' ',$bann->descweb_hces1);
        $bann->descweb_hces1 = str_replace ('</p>',' ',$bann->descweb_hces1);

        $titulo ="$bann->ref_asigl0  -  $bann->descweb_hces1";
    }elseif(!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
        $titulo = $bann->titulo_hces1;
    }elseif(\Config::get('app.ref_asigl0')){
        $titulo = trans(\Config::get('app.theme').'-app.lot.lot-name') ." ".$bann->ref_asigl0 ;
    }
?>
    <div>
        <div class="item_home">
            <a title="{{$title}}" <?= $url ?> >
                <div class="border_item_img">
                    <div class="item_img">
                        <img src="/img/load/lote_medium/{{$img->getloteImg($bann)}}" alt="{{ $titulo}}">
                    </div>
                    @if(( $bann->retirado_asigl0 !='N') || (\Config::get('app.awarded') && $bann->cerrado_asigl0 == 'S' &&  !empty($bann->max_puja)) )
                        <div class="no_dispo-band">
                            <div class="no_dispo"></div>
                            <p>{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                        </div>
                    @endif
                </div>
            </a>
            @if(!empty($titulo))
               <div class="title_item">
                   <a title="{{$title}}" <?= $url ?> >
                       <h4><?= $titulo ?></h4>
                   </a>
               </div>
            @endif
            <div class="desc_lot carousel_p">
                        <?= $bann->desc_hces1 ?>
            </div>
            <div class="data-price">
            @if( $bann->retirado_asigl0 =='N')
                @if( \Config::get('app.estimacion') || \Config::get('app.impsalhces_asigl0'))
                    <p class="salida">
                        @if( \Config::get('app.estimacion'))
                            {{ trans(\Config::get('app.theme').'-app.lot.estimate') }} <span class=""> {{$bann->imptas_asigl0}} -  {{$bann->imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                        @elseif( \Config::get('app.impsalhces_asigl0'))
                            {{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}  <span class=""> {{($bann->impsalhces_asigl0)}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                        @endif
                    </p>
                @endif

                <p class="salida">
                    @if (($bann->tipo_sub == 'O' ||   $bann->tipo_sub == 'P') &&  $bann->max_puja > 0)
                        {{ trans(\Config::get('app.theme').'-app.home.puja_actual') }} <span>{{ \Tools::Moneyformat($bann->max_puja)}} €</span>
                    @elseif($bann->tipo_sub == 'O' ||   $bann->tipo_sub == 'P')
					<?php /* no quieren texto trans(\Config::get('app.theme').'-app.lot_list.no_bids')*/ ?>
                    @endif
                </p>
           @if( \Config::get('app.awarded'))
                    <p class="salida">
                        @if($bann->cerrado_asigl0 == 'D')
                            {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                        @elseif($bann->cerrado_asigl0 == 'S' && !empty($bann->max_puja) && $bann->remate_asigl0 =='S' )
                            {{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}: <span >{{$bann->max_puja}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                        @elseif($bann->cerrado_asigl0 == 'S' &&  !empty($bann->max_puja) &&  $bann->remate_asigl0 !='S')
                            {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                        @elseif($bann->cerrado_asigl0 == 'S' &&  empty($bann->max_puja))
                            {{ trans(\Config::get('app.theme').'-app.lot_list.available') }}
                        @elseif( $bann->retirado_asigl0 !='N' || ($bann->cerrado_asigl0 == 'D' || $bann->cerrado_asigl0 == 'R') || (\Config::get('app.awarded') && $bann->cerrado_asigl0 == 'S' &&  !empty($precio_venta)) ))
                            {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}
                        @endif

                    </p>
                @endif

                @if(($bann->tipo_sub == 'P' || $bann->tipo_sub == 'O') && $bann->cerrado_asigl0=='N')
                    <p class="salida text-center">
                        <i class="fa fa-clock-o"></i>
                        <span
                            data-countdown="{{strtotime($bann->close_at) - getdate()[0] }}"
                            data-format="<?= \Tools::down_timer($bann->close_at); ?>"
                            class="timer">
                        </span>
                    </p>
                @endif
             @endif
        </div>
    </div>
</div>


