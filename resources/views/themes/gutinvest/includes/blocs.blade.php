<?php
$type= !empty($data['type'])? $data['type'] : null;
$subc_sub= !empty($data['subc_sub'])? $data['subc_sub'] : 'S';
?>


                <div class="col-xs-12 col-md-10 col-md-offset-1 text-center">
                    <div class="view-subastas-lista">
                        <ul class="nav nav-tabs items-lista-subasta" role="tablist">
                            <?php
                                $blocs_obj = new  App\Models\BlocSector();
                               
                                $blocs = $blocs_obj->get_active_blocs($type,$subc_sub);      
                                 
                                $blocs_auctions = $blocs_obj->get_auction_blocs($type,$subc_sub);  
                                
                                $sectors = $blocs_obj->get_sectors();                               
                            ?>
                                

                            @if(!empty($blocs_auctions))
                                <div id="total_lots_home" class="hide">{{count($blocs_auctions['ALL'])}}</div>
                                <li role="presentation" class="active subasta-tipo-click"><a href="#bloc_ALL" aria-controls="bloc_ALL" role="tab" data-toggle="tab"><?= trans(\Config::get('app.theme').'-app.subastas.all')  ?></a></li>
                            @endif
                            @foreach($blocs as $bloc)  
                                <li role="presentation" class="subasta-tipo-click"><a href="#bloc_{{$bloc->cod_bloc}}" aria-controls="bloc_{{$bloc->cod_bloc}}" role="tab" data-toggle="tab">{{$bloc->des_bloc}}</a></li>
                            @endforeach
                        </ul>
                         
                    </div>
                </div>
           

  <!-- Tab panes -->
 
  @if(!(empty($home_page)))
  <div class="tab-content col-xs-12 col-md-10 col-md-offset-1">
@else
<div class="tab-content col-xs-12 col-xs-offset-0 no-padding">
    @endif


    
                        
                        
                        
                        
                         <?php //ponemos las subastas correspondientes a cada bloc ?>
                        @foreach($blocs_auctions as $key_bloc => $auctions)     
                        
                            <div role="tabpanel" class="tab-pane <?= $key_bloc=='ALL'? 'active':'' ; ?>" id="bloc_{{$key_bloc}}">
                                @if(empty($home_page))
                                    <div class="col-xs-12">
                                        <div><small><strong>Filtrar por <span class="delete-auc" style="display: none;">X</span></strong></small></div>
                                        <ul class="subsecciones-subastas">
                                            <!-- marcar los sectores que ya se ha nescrito -->  
                                            <?php  $sectors_printed = array(); ?>
                                            @foreach($auctions as $subasta)
                                            
                                                @if(!empty($subasta->cod_subsector) && !in_array($subasta->cod_subsector,$sectors_printed ) )      
                                                    <?php  $sectors_printed [] = $subasta->cod_subsector; ?>
                                                    <li class="seleccion-subcat" data-id="sec_{{$subasta->cod_subsector}}"><small>{{$sectors[$subasta->cod_subsector]}}</small></li>
                                                @endif

                                            @endforeach
                                        </ul>

                                    </div>
                                @endif

                                @foreach($auctions as $subasta)
                               
                                    <?php
                                                                
                                        //subcategoria de la subasta
                                        $cat = "sec_$subasta->cod_subsector";
                                        $url_lotes=\Routing::translateSeo('subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;                
                                        $url_tiempo_real=\Routing::translateSeo('api/subasta').$subasta->cod_sub."-".str_slug($subasta->name)."-".$subasta->id_auc_sessions;
                                        $url_subasta=\Routing::translateSeo('info-subasta').$subasta->cod_sub."-".str_slug($subasta->name);
                                        $dataEnd = date_create($subasta->orders_end);
                                        
                                    ?>  
                                    @include('includes.auctionlist')
                                @endforeach
                                
                                
                                @if(!empty($home_page) && $home_page == true &&  count($auctions) > 3 )
                                    <div class="col-xs-10 col-xs-offset-1 text-right">
                                        <p class="ver_mas">
                                          <a href="{{ \Routing::translateSeo('todas-subastas') }}">{{ trans(\Config::get('app.theme').'-app.lot_list.see_more') }}</a>
                                      </p>
                                    </div>
                                @endif
                                
                             
                                
                            </div>
                        @endforeach
                       
                      
            
           
            </div>
            
  
  <script>
      $('.subasta-tipo-click').click(function(){
          $('.item-subasta').show()
          $('.subsecciones-subastas').find('.selected').removeClass('selected')
          $('.delete-auc').hide()
          
      })
      $('.delete-auc').click(function(){
          $('.item-subasta').show()
          $('.subsecciones-subastas').find('.selected').removeClass('selected')
          $(this).hide()
      })
      $('.seleccion-subcat').click(function(){
           $('.delete-auc').show()
          $('.item-subasta').show()
          $('.subsecciones-subastas').find('.selected').removeClass('selected')
          $(this).addClass('selected')
          var subcat = $(this).attr('data-id');
          $('.item-subasta').each(function(){
            if(!($(this).hasClass(subcat))) {
            $(this).hide()
        }
            
    })
      })
        
  </script>