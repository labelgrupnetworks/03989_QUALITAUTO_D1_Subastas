
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
<div class="lotes-carousel">
        <div class="item_home">
            <a title="{{ $titulo}}" <?= $url ?> >          
                <div class="border_item_img">
                    <div class="item_img">                        
                        <img src="/img/load/lote_medium/{{$img->getloteImg($bann)}}" alt="{{ $titulo}}">
                        @if( $bann->retirado_asigl0 !='N')
                            <div class="retired">{{ trans(\Config::get('app.theme').'-app.lot.retired') }}</div>
                        @elseif(\Config::get('app.awarded') && $bann->cerrado_asigl0 == 'S' &&  !empty($bann->max_puja) )
                            <div class="retired" style ="">
                                {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                            </div>
                        @endif                  
                    </div>
                </div>
            </a>
            @if(!empty($titulo))
               <div class="title_item">
                   <a title="{{ $titulo}}" <?= $url ?> >               
                       <span class="title-lote-carousel">{{ $titulo}}</span>              
                   </a>
               </div>
            @endif
            @if( ( \Config::get( 'app.descweb_hces1' ) ) ||  ( \Config::get( 'app.desc_hces1' )))
                <div class="desc_lot">
                        @if( \Config::get('app.descweb_hces1'))
                            <?= $bann->descweb_hces1 ?>
                        @elseif ( \Config::get('app.desc_hces1' ))
                            <?= $bann->desc_hces1 ?>
                        @endif
                </div>
            @endif
            <div class="data-price">
            @if( $bann->retirado_asigl0 =='N')
                @if( \Config::get('app.estimacion') || \Config::get('app.impsalhces_asigl0'))
                    <p class="salida">
                        @if( \Config::get('app.estimacion'))
                            {{ trans(\Config::get('app.theme').'-app.lot.estimate') }} <span class="num-lot"> {{$bann->imptas_asigl0}} -  {{$bann->imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                        @elseif( \Config::get('app.impsalhces_asigl0'))
                            {{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}  <span class="num-lot"> {{$bann->impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                        @endif
                    </p>
                @endif
                                @if( \Config::get('app.awarded'))
                    <p class="salida">
                        
                        @if($bann->cerrado_asigl0 == 'D')
                    {{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</div>
                        @elseif($bann->cerrado_asigl0 == 'S' && !empty($bann->max_puja) && $bann->remate_asigl0 =='S' )    
                        {{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}: <span >{{$bann->max_puja}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</span>
                        @elseif($bann->cerrado_asigl0 == 'S' &&  !empty($bann->max_puja) &&  $bann->remate_asigl0 !='S')
                            {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                        @elseif($bann->cerrado_asigl0 == 'S' &&  empty($bann->max_puja))
                            {{ trans(\Config::get('app.theme').'-app.subastas.dont_buy') }}
                        @endif
                    </p>
                @endif
                <p class="salida">
                    @if (($bann->tipo_sub == 'O' ||   $bann->tipo_sub == 'P') &&  $bann->max_puja > 0)
                        {{ trans(\Config::get('app.theme').'-app.home.puja_actual') }} <span>{{ $bann->max_puja}} €</span>
                    @elseif($bann->tipo_sub == 'O' ||   $bann->tipo_sub == 'P')
                        {{ trans(\Config::get('app.theme').'-app.home.sin_pujas') }}
                    @endif
                </p>
           
                <div class="divider-lot"></div>
                <div class="lot-btn-clock">
                    <div class="lot-btn"><a title="{{$titulo}}" <?= $url ?> >{{ trans(\Config::get('app.theme').'-app.sheet_tr.view') }}</a></div>
                    @if(($bann->tipo_sub == 'P' || $bann->tipo_sub == 'O') && $bann->cerrado_asigl0=='N')
                    <p class="salida text-center" style="margin-left: 12px;">
                        <i class="fa fa-clock-o"></i>
                        <span 
                           data-countdown="{{strtotime($bann->close_at) - getdate()[0] }}"
                            data-format="<?= \Tools::down_timer($bann->close_at); ?>" 
                            class="timer">
                        </span>
                    </p>            
                @endif 
                </div>
                
             @endif
        </div>
    </div>
</div>