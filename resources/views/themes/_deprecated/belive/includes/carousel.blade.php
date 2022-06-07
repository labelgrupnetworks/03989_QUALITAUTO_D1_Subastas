
 <?php
    # al carrousel no deberían llegar lotes cerrados, devueltos, retirados ni Ocultos
    
   
         
        $url = \Tools::url_lot($bann->sub_asigl0,$bann->id_auc_sessions,$bann->name,$bann->ref_asigl0,$bann->num_hces1,$bann->webfriend_hces1,$bann->titulo_hces1);
        $url = "href='$url'";    
        
   
    
    $a = explode("-",$bann->descweb_hces1);
    $titulo = $bann->ref_asigl0." - ".$a[0];

    $hay_pujas = !empty($bann->max_puja)? true : false; 
    $subasta_online = ($bann->tipo_sub == 'P' || $bann->tipo_sub == 'O')? true : false; 
    $subasta_abierta_P = $bann->subabierta_sub == 'P'? true : false;

    use App\libs\Currency;
    $currency = new Currency();      
    $divisas = $currency->getAllCurrencies();

?>
    <div style="position: relative; padding: 0 10px;"  >
        
            <a class="lote-destacado-link secondary-color-text" title="{{ $titulo}}" <?= $url ?> >  
                            
        <div class="item_home">
                <div class="border_item_img">
                    <div class="item_img">  
                        
                        <div data-loader="loaderDetacados" class='text-input__loading--line'></div>
                        <div class='degradado'></div>
                        <img class="lazy" data-src="{{Tools::url_img('lote_medium',$bann->num_hces1,$bann->lin_hces1)}}" alt="{{ $titulo}}" />
                        @if (isset($a[1]))
                            <span>{{ $a[1] }}</span>
                        @endif

                    </div>
                        
                </div>

            <div class="content_item">
            
               <div class="title_item text-left mt-3">
                    <span class="seo_h4 text-left">{{ $titulo}}</span>              
               </div>
            
            
                <div class="data-price">

                    <div class="row">
                        <div class="salida text-center col-xs-6">                       
                            <p class="salida-title mb-0">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>  
                            <div class="salida-title letter-price-salida"> {{ $bann->impsalhces_asigl0 }} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                            <div value="{{$bann->no_formated_impsalhces_asigl0}}" class="js-divisa">{{ \Tools::MoneyFormat($divisas['USD']->impd_div * $bann->no_formated_impsalhces_asigl0)  }}</div>
                            
                        </div>

                        @if ( ($subasta_online || $subasta_abierta_P) &&  $hay_pujas)
                            <div class="salida fs-12 text-center col-xs-6 no-padding">
                                <p class="mb-0 salida-title">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p> 
                                <div class="letter-price-salida salida-title">{{ \Tools::moneyFormat($bann->max_puja,false,0)}} €</div>
                                <div value="{{$bann->max_puja}}" class="js-divisa"></div>
                            </div>
                        @endif
                    </div>
                    

                    @if($subasta_online)
                        <p class="mt-15 salida-time background-principal text-center  d-flex align-items-center justify-content-center">
                            {{ trans(\Config::get('app.theme').'-app.lot.place_bid') }}
                            <span 
                                data-countdown="{{strtotime($bann->close_at) - getdate()[0] }}"
                                data-format="<?= \Tools::down_timer($bann->close_at); ?>" 
                                class="timer">
                            </span>
                        </p>            
                    @endif
                </div>
            </div>
    </div>
</a>
</div>

