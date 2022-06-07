<?php $lang =  App::getLocale() ?>
<?php
            if(!empty($_GET['finished'])){
                foreach($data['auction_list'] as $key => $sub_finished){
                    if(strtotime($sub_finished->session_end) <= time() && $_GET['finished'] == 'false'){
                        unset($data['auction_list'][$key]);
                    }
                    elseif(strtotime($sub_finished->session_end) > time() && $_GET['finished'] == 'true'){
                        unset($data['auction_list'][$key]);
                    }
                }
				#reordenamos el array para que aparezcan los nuevos primero
				if($_GET['finished'] == 'true'){
					$data['auction_list'] = array_reverse ($data['auction_list']);
				}
            }
        ?>

<div class="all-auctions color-letter">
        <div class="container"> 
<!-- acceder a la pagina historico sin estar autenticado...la pagina le muestra la opcion de registrarse  -->
        @if( !Session::has('user') && $data['subc_sub'] == 'H')
            <div class="row">
                <div class="col-md-12">

                    <div class="row">
                        <div class="col-xs-12 border-content" style="text-align: left">
                            <h4 class="valoracion-h4">{{ trans(\Config::get('app.theme').'-app.valoracion_gratuita.confirmar_registro') }}</h4>
                            <p>{{ trans(\Config::get('app.theme').'-app.subastas.historic_not_register') }}</p>
                            <button class="btn_login button-principal" style="margin-bottom: 10px">{{ trans(\Config::get('app.theme').'-app.login_register.register') }}</button>

                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="row">
                    <div class="auctions-list col-xs-12">                       
                            @foreach ($data['auction_list'] as  $subasta)
                            <?php 
                             
                                $indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
                                if(count($indices) > 0 ){       
                                    $url_lotes= \Tools::url_indice_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
                                }else{
                                    $url_lotes= \Tools::url_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
                                }
                                $url_tiempo_real=\Tools::url_real_time_auction($subasta->cod_sub,$subasta->name,$subasta->id_auc_sessions);
                                $url_subasta=\Tools::url_info_auction($subasta->cod_sub,$subasta->name);
                
                            ?>
                        <div class="col-xs-12 col-md-6">
                            
                            <div class="col-xs-12 border-lot auction-container flex-wrap d-flex align-items-center">
                                @if( $subasta->tipo_sub =='W' &&   strtotime($subasta->session_end) > time() )
                                <div class="bid-online"></div>   
                                <div class="bid-online animationPulseRed"></div> 
                                @endif

                            <div class="col-xs-12 col-md-4 no-padding auction-item-img">
                                            <div class="auction-size d-flex align-items-center"> 
                                                <div data-loader="loaderDetacados" class='text-input__loading--line'></div>   
                                                    <img 
                                                    data-src="/img/load/subasta_medium/AUCTION_{{ $subasta->emp_sub }}_{{ $subasta->cod_sub }}.jpg" 
                                                    alt="{{ $subasta->name }}" 
                                                        class="lazy img-responsive"
                                                        style="display: none"                                
                                                    />  
                                                </div>                 
                                                        
                            </div>
                            <div class="col-xs-12 col-md-4 auction-desc-content">
                                    <div class="auction-list-title">{{$subasta->name }}</div>
                                    {{-- <small>{{$subasta->des_sub }}</small> --}}
                                    <br>
                                    <div>
                                        <small style="font-weight: 600;">{{ trans(\Config::get('app.theme').'-app.subastas.start_orders') }}</small>
                                    </div>
                                    <div>
                                        <small>{{$subasta->orders_start }}</small>
                                        
                                </div>
    
                                <div class="documents">
    
                                        <ul class="ul-format">
                                               <?php 
                                               $pdf_cat = Tools::url_pdf($subasta->cod_sub,$subasta->reference,'cat');
                                               $pdf_man = Tools::url_pdf($subasta->cod_sub,$subasta->reference,'man');
                                               $pdf_pre = Tools::url_pdf($subasta->cod_sub,$subasta->reference,'pre');
                                               //Ya no se usa desde el ERP, lo mantengo por si acaso
                                               $pdf_adj = Tools::url_pdf($subasta->cod_sub,$subasta->reference,'adj');
                                               ?>
                                                @if($pdf_cat)
                                                    <li class="col-md-12 col-xs-6 no-padding">
                                                        <a target="_blank" class="cat-pdf color-letter d-flex" href="{{$pdf_cat}}" role="button">
                                                            <div class="text-center"><i class="fas  fa-file-download"></i></div>
                                                            <small>{{ trans(\Config::get('app.theme').'-app.subastas.pdf_catalog') }}</small>
                                                        </a>
                                                    </li>
                                                @endif
                                                
                                                @if($pdf_man)
                                                    <li class="col-md-12 col-xs-6 no-padding">
                                                        <a target="_blank" class="cat-pdf color-letter d-flex" href="{{$pdf_man}}" role="button">
                                                            <div class="text-center"><i class="fas fa-file-download"></i></div>
                                                            <small>{{ trans(\Config::get('app.theme').'-app.subastas.pdf_man') }}</small>
                                                        </a>
                                                    </li>                                                   
                                                @endif
                                                
                                                 @if($pdf_pre)
                                                    <li class="col-md-12 col-xs-6 no-padding">
                                                        <a target="_blank" class="cat-pdf color-letter d-flex" href="{{$pdf_pre}}" role="button">
                                                            <div class="text-center"><i class="fas fa-file-download"></i></div>
                                                            <small>{{ trans(\Config::get('app.theme').'-app.subastas.pdf_pre') }}</small>
                                                        </a>
                                                    </li>                                                   
                                                @endif
                                                
                                                 @if($pdf_adj)
                                                    <li class="col-md-12 col-xs-6 no-padding">
                                                        <a target="_blank" class="cat-pdf color-letter d-flex" href="{{$pdf_adj}}" role="button">
                                                            <div class="text-center"><i class="fas fa-file-download"></i></div>
                                                            <small>{{ trans(\Config::get('app.theme').'-app.subastas.pdf_adj') }}</small>
                                                        </a>
                                                    </li>                                                   
                                                @endif
                                        </ul>
                                    </div>
    
                            </div>
                            <div class="col-xs-12 col-md-4">
                                    <div class="auction-item-links">
                                                <div class="auction-item-icon-desc d-block">
                                                    <a title="{{ $subasta->name }}" href="{{ $url_lotes }}" class=" btn-view-lots button-principal">{{ trans(\Config::get('app.theme').'-app.subastas.see_lotes') }}</a>
                                                </div>    
                                            <div class="auction-item-icon-desc  d-block">
                                                <a title="{{ $subasta->name }}" href="{{ $url_subasta }}" class="btn-info-auction secondary-button">{{ trans(\Config::get('app.theme').'-app.subastas.see_subasta') }}</a>
                                            </div> 
                                            @if( $subasta->tipo_sub =='W' &&   strtotime($subasta->session_end) > time() )
                                                <div class="bid-life d-block">
                                                    <a  style="color:#FFFFFF" class="btn-bid-life d-block "  href="{{ $url_tiempo_real }}" title="{{ trans(\Config::get('app.theme').'-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_start),'d/m/Y H:i') }} {{ trans(\Config::get('app.theme').'-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s',$subasta->session_end),'d/m/Y H:i') }}" target="_blank">{{ trans(\Config::get('app.theme').'-app.lot.bid_live') }}</a>
                                                </div>
                                            @endif     
                                        
                                    </div>                
                            </div>
                        </div>
                        </div>
                        @endforeach     
                    </div>
                </div>                    
        @endif
            </div>


            </div>
      
    