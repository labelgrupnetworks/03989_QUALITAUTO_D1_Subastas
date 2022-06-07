
 <?php
    # al carrousel no deberían llegar lotes cerrados, devueltos, retirados ni Ocultos
    
   
         
        $url = \Tools::url_lot($bann->sub_asigl0,$bann->id_auc_sessions,$bann->name,$bann->ref_asigl0,$bann->num_hces1,$bann->webfriend_hces1,$bann->titulo_hces1);
        $url = "href='$url'";    
        
   
    
    $titulo ="$bann->ref_asigl0  -  $bann->titulo_hces1";        
    $hay_pujas = !empty($bann->max_puja)? true : false; 
    $subasta_online = ($bann->tipo_sub == 'P' || $bann->tipo_sub == 'O')? true : false; 
    $subasta_abierta_P = $bann->subabierta_sub == 'P'? true : false;
    
    $precio_salida = (!empty($bann->impsalweb_asigl0) && $bann->impsalweb_asigl0) != 0 ? \Tools::moneyFormat($bann->impsalweb_asigl0) : $bann->impsalhces_asigl0;
?>
    <div style="position: relative; padding: 0 10px;"  >
        
            <a class="lote-destacado-link secondary-color-text" title="{{ $titulo}}" <?= $url ?> >  
                            
        <div class="item_home">
                <div class="border_item_img">
                    <div class="item_img">  
                        
                        <div data-loader="loaderDetacados" class='text-input__loading--line'></div>                      
                        <img class="lazy" data-src="{{Tools::url_img('lote_medium',$bann->num_hces1,$bann->lin_hces1)}}" alt="{{ $titulo}}" />             
                    </div>
                </div>

               {{-- @if($item->cod_sub == "SUBALIAV")
                    <div class="retired ">
                        Venta Directa
                    </div>
                @elseif($item->cod_sub == "SUBALW")
                    <div class="retired ">
                        Presencial
                    </div>
                @elseif($item->cod_sub == "SUBALIAW")
                    <div class="retired ">
                        Presencial
                    </div>
                @elseif($item->cod_sub == "SUBALO")
                    <div class="retired ">
                        Online
                    </div>
                @endif --}}

                <div class="lote">Lote: </div>
               <div class="title_item text-left mt-3">
                       <span class="seo_h4 text-left">{{ Str::limit($titulo, 40) }}</span>              
               </div>
            
                @if($subasta_online)
                    <p class="mt-15 salida-time background-principal text-center  d-flex align-items-center justify-content-center">
                        <i class="fas fa-clock"></i>
                        <span 
                            data-countdown="{{strtotime($bann->close_at) - getdate()[0] }}"
                            data-format="<?= \Tools::down_timer($bann->close_at); ?>" 
                            class="timer">
                        </span>
                    </p> 
                @else
                    <span class="d-block mt-15"></span>           
                @endif

            <div class="data-price">
            
                <div class="row">
                    <div class="salida col-xs-12 text-center ">                       
                        <p class="salida-title mb-0 col-xs-8">{{ trans(\Config::get('app.theme').'-app.lot.lot-price') }}</p>  
                        <div class="salida-title mt-0 letter-price-salida col-xs-4"> {{\Tools::moneyFormat($bann->impsalhces_asigl0,false,0)}} {{ trans(\Config::get('app.theme').'-app.subastas.euros') }}</div>
                        </div>

                    <div class="salida fs-12 col-xs-12 text-center">
                        @if ( ($subasta_online || $subasta_abierta_P) &&  $hay_pujas)
                            <p class="mb-0 salida-title col-xs-8">{{ trans(\Config::get('app.theme').'-app.lot.puja_actual') }}</p> 
                            <div class="letter-price-salida salida-title mt-0 col-xs-4">{{ \Tools::moneyFormat($bann->max_puja,false,0)}} €</div>

                        @endif
                    </div>
                </div>
        </div>
        <div class="pujar">
            Pujar
        </div>
    </div>
</a>
</div>

