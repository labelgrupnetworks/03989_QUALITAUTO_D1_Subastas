
 <?php
 
 
    $url = "";
    //Si no esta retirado tendrá enlaces
    if($bann->retirado_asigl0 =='N'){       
        $url = \Tools::url_lot($bann->sub_asigl0,$bann->id_auc_sessions,$bann->name,$bann->ref_asigl0,$bann->num_hces1,$bann->webfriend_hces1,$bann->titulo_hces1);
        $url = "href='$url'";    
        
    }
    
    $titulo ="";
    if(\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
        $titulo ="$bann->ref_asigl0  -  $bann->descweb_hces1";
    }elseif(!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
        $titulo = $bann->titulo_hces1;        
    }elseif(\Config::get('app.ref_asigl0')){
        $titulo = trans(\Config::get('app.theme').'-app.lot.lot-name') ." ".$bann->ref_asigl0 ;
    }          
?>
    <div style="position: relative; padding: 0 10px;"  >
        
            <a class="lote-destacado-link secondary-color-text" title="{{ $bann->titulo_hces1}}" <?= $url ?> >  
                    @if( $bann->retirado_asigl0 !='N')
                    <div class="retired">{{ trans(\Config::get('app.theme').'-app.lot.retired') }}</div>
                @elseif(\Config::get('app.awarded') && $bann->cerrado_asigl0 == 'S' &&  !empty($bann->max_puja) )
                    <div class="retired">
                        {{ trans(\Config::get('app.theme').'-app.subastas.buy') }}
                    </div>                        
                @endif            
        <div class="item_home">
                <div class="border_item_img">
                    <div class="item_img">  
                        
                        <div data-loader="loaderDetacados" class='text-input__loading--line'></div>                      
                        <img class="lazy" data-src="{{Tools::url_img('lote_medium',$bann->num_hces1,$bann->lin_hces1)}}" alt="{{ $bann->titulo_hces1}}" />             
                    </div>
                </div>

            @if(!empty($bann->descweb_hces1))
               <div class="title_item">
                       <span class="seo_h4" style="text-align: center;"><?= $titulo ?></span>              
               </div>
            @endif

            <div class="data-price">
            @if( $bann->retirado_asigl0 =='N')
                @if( \Config::get('app.estimacion') || \Config::get('app.impsalhces_asigl0'))
                    <p class="salida">
                        @if( \Config::get('app.estimacion'))
                            <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.estimate') }}</p> 
                            <div class=""> {{$bann->imptas_asigl0}} -  {{$bann->imptash_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                        <?php //no debe aparecer el precio de salidade lotes cerrados  ?>
                        @elseif( \Config::get('app.impsalhces_asigl0') && ($bann->cerrado_asigl0 == 'N' || empty($bann->max_puja)) )
                            <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>  
                            <div class=""> {{$bann->impsalhces_asigl0}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                        @endif
                    </p>
                @endif
                <p class="salida">
                    @if (($bann->tipo_sub == 'O' ||   $bann->tipo_sub == 'P') &&  $bann->max_puja > 0)
                        <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.home.puja_actual') }}</p> <div>{{ $bann->max_puja}} €</div>
                    @elseif($bann->tipo_sub == 'O' ||   $bann->tipo_sub == 'P')
                        <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.home.sin_pujas') }}</p>
                    @endif
                </p>
                
                @if( \Config::get('app.awarded'))
                    <p class="salida">
                        @if($bann->cerrado_asigl0 == 'D')
                        <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.dont_available') }}</p>
                        @elseif($bann->cerrado_asigl0 == 'S' && !empty($bann->max_puja) && $bann->remate_asigl0 =='S' )    
                            <p class="salida-title extra-color-one">{{ trans(\Config::get('app.theme').'-app.subastas.buy_to') }}</p> <div >{{$bann->max_puja}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                        @elseif($bann->cerrado_asigl0 == 'S' &&  !empty($bann->max_puja) &&  $bann->remate_asigl0 !='S')
                        <p class="salida-title">{{ trans(\Config::get('app.theme').'-app.subastas.buy') }}</p>
                        <?php // no vendido no tiene que mostrarse ?>
                        @elseif($bann->cerrado_asigl0 == 'S' &&  empty($bann->max_puja))
                            <p class="salida-title"></p>
                        @endif
                    </p>
                @endif
                
           

                @if(($bann->tipo_sub == 'P' || $bann->tipo_sub == 'O') && $bann->cerrado_asigl0=='N')
                    <p class="salida-time background-principal text-center">
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
</a>
</div>

