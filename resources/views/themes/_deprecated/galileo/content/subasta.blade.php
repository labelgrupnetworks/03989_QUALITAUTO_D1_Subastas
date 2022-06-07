<script>
var cod_sub = '{{$data["cod_sub"]}}';
routing.node_url 	 = '{{ Config::get("app.node_url") }}';
routing.comprar		 = '{{ $data["node"]["comprar"] }}';
routing.ol		 = '{{ $data["node"]["ol"] }}';
</script>

<form id="form_lotlist" method="get" action="{{ $data['url'] }}">
<div class="auction-lots min-height">
    <div class="container">
        <div class="row" style="position: relative">
            <div class="order-views col-xs-12 no-padding">
                <div class="col-xs-12 col-lg-3">
                        
                        <select id="order_selected" name="order" class="form-control submit_on_change">
                                <option value="name" @if (app('request')->input('order') == 'name') selected @endif >
                                     {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   {{ trans(\Config::get('app.theme').'-app.lot_list.name') }}
                                </option>
                                <option value="price_asc" @if (app('request')->input('order') == 'price_asc') selected @endif >
                                     {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.price_asc') }}
                                </option>
                                <option value="price_desc" @if (app('request')->input('order') == 'price_desc') selected @endif >
                                    {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:      {{ trans(\Config::get('app.theme').'-app.lot_list.price_desc') }}
                                </option>
                                <option value="ref" @if (empty(app('request')->input('order')) || app('request')->input('order') == 'ref') selected @endif >
                                     {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:     {{ trans(\Config::get('app.theme').'-app.lot_list.reference') }}
                                </option>
                                @if(!empty( $data['subastas']) && ($data['subastas'][0]->tipo_sub == 'O' || $data['subastas'][0]->tipo_sub == 'P'))
                                    <option value="ffin" @if (app('request')->input('order') == 'ffin') selected @endif >
                                            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.more_near') }} </b>
                                    </option>
            
                                    <option value="mbids" @if (app('request')->input('order') == 'mbids') selected @endif >
                                            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.more_bids') }} </b>
                                    </option>
            
                                    <option value="hbids" @if (app('request')->input('order') == 'hbids') selected @endif >
                                            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:   <b>   {{ trans(\Config::get('app.theme').'-app.lot_list.higher_bids') }} </b>
                                    </option>
            
            
                                    <option value="fecalta" @if (app('request')->input('order') == 'fecalta') selected @endif >
                                            {{ trans(\Config::get('app.theme').'-app.lot_list.order') }}:    {{ trans(\Config::get('app.theme').'-app.lot_list.more_recent') }} 
                                    </option>
                                @endif
                        </select>
                       
                        
                </div>
                <div class="col-xs-12 col-lg-9 d-flex views widgets-auction pull-right">
                    <?php // si es uan subasta w y abierta o si es uan subasta tipo O o P ?>
                    @if(!empty( $data['subastas']) && ( ($data['subastas'][0]->tipo_sub == 'W' && $data['subastas'][0]->subabierta_sub == 'S') || $data['subastas'][0]->tipo_sub == 'P'  || $data['subastas'][0]->tipo_sub == 'O' )  && ($data['subastas'][0]->subc_sub == 'A' ||$data['subastas'][0]->subc_sub == 'S' )  )
                        <div class="full-screen widget d-inline-flex">    
                            <a class="refresh d-block color-letter" href=""> {{ trans(\Config::get('app.theme').'-app.lot_list.refresh_prices') }} <i class="fa fa-refresh" aria-hidden="true"></i></a>
                        </div>
                    @endif
                    @if(!empty($data['sub_data']) && !empty($data['sub_data']->opcioncar_sub && !empty($data['subastas'][0])) && $data['sub_data']->opcioncar_sub == 'S' && strtotime($data['subastas'][0]->start_session) > time())
                        <div class="full-screen widget d-inline-flex">
                            @if(Session::has('user'))
                                <i class="fa fa-gavel  fa-1x"></i> <a href="{{ \Routing::slug('user/panel/modification-orders') }}?sub={{$data['sub_data']->cod_sub}}" ><?= trans(\Config::get('app.theme').'-app.lot_list.ver_ofertas') ?></a>
                            @endif
                        </div>
                    @endif
                    
                   

                    <div class="input-order-quantity hidden-xs hidden-sm" style="margin-right: 5px">
                            <select name="total" class="form-control submit_on_change" style="height: 50px; border-radius: 0;">
                                @foreach (\Config::get('app.filter_total_shown_options') as $option)
                                    <option value="{{ $option }}" @if (app('request')->input('total') == $option) selected @endif >
                                            <label>{{ trans(\Config::get('app.theme').'-app.lot_list.to_show') }}</label> {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                   


                        @if(!empty( $data['subastas']) && $data['subastas'][0]->tipo_sub == 'W'  && ($data['subastas'][0]->subc_sub == 'A' ||$data['subastas'][0]->subc_sub == 'S' )  && strtotime($data['subastas'][0]->start_session) > time())
                        <div  class="widget text-right d-flex align-items-center justify-content-center timeLeft">
                                <span data-countdown="{{ strtotime($data['subastas'][0]->start_session) - getdate()[0] }}"  data-format="<?= \Tools::down_timer($data['subastas'][0]->start_session); ?>" data-closed="{{ $data['subastas'][0]->cerrado_asigl0 }}" class="timer"></span>
                                <span class="clock"></span>
                            </div>            
                    @endif


                    <?php
                        $subasta_finalizada = false;
                        if(!empty($data['sub_data'])){
                            //ver si la subasta está cerrada                    
                            $SubastaTR      = new \App\Models\SubastaTiempoReal();
                            $SubastaTR->cod = $data['sub_data']->auction;
                            $SubastaTR->session_reference =  $data['sub_data']->reference; //$subasta->get_reference_auc_session($subasta->id_auc_sessions);      
                            $status  = $SubastaTR->getStatus();

                            if(!empty($status) && $status[0]->estado == "ended" ){                        
                                $subasta_finalizada = true;                    

                            }
                        }
                    ?>
                    @if(!empty($data['sub_data']) && $data['sub_data']->tipo_sub =='W' && strtotime($data['sub_data']->end) > time() && strtotime($data['sub_data']->start) < time() && $subasta_finalizada == false)
                        <?php 
                            //en caso de que este el tiempo real pujando en ese momento, activamos un texto que le
                            //avisa al cliente y lo dirige a pujar en vivo.
                        $url_tiempo_real = \Tools::url_real_time_auction($data['sub_data']->cod_sub,$data['sub_data']->name,$data['sub_data']->id_auc_sessions);    
                        ?>   
                        <div class=" widget full-screen d-inline-flex" style="position: relative">
                            <div class="bid-online"></div>
                            <div class="bid-online animationPulseRed"></div>
                            <a href="{{ $url_tiempo_real }}" target="_blank" class="bid-live grid-icon-square color-letter d-flex">{{ trans(\Config::get('app.theme').'-app.lot_list.bid_live') }}</a>
                        </div>
                    @endif
                    <div class="views-content d-inline-flex">
                            <div class="title-views d-flex align-items-center justify-content-center">{{ trans(\Config::get('app.theme').'-app.lot_list.view') }}</div>
                            <a id="square" class="grid-icon-square color-letter d-block" href="javascript:;"><i class="fas fa-th"></i></a>
                            <a id="large_square" class="grid-icon-square d-block color-letter" href="javascript:;"><i class="fas fa-bars"></i></a>
                    </div>
                    <div class="hidden-xs widget full-screen d-inline-flex">
                            <a id="full-screen" class="grid-icon-square color-letter d-flex" href="javascript:;"><i class="fas fa-expand"></i></a>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container auction-container-lots">
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3">
            <?php 
                $inf_subasta = new \App\Models\Subasta();
                if(!empty($data['sub_data'])){
                    $inf_subasta->cod = $data['sub_data']->cod_sub;
                }else{
                    $inf_subasta->cod = $data['cod_sub'];
                }
                $ficha_subasta=$inf_subasta->getInfSubasta();
            ?>
            @include('includes.subasta_filters')
        </div>
 
        <div class="col-xs-12 col-sm-8 col-md-9 list_lot_content no-padding">

            <div class="list_lot">

       
                
                @foreach ($data['subastas'] as $key => $item)
                    <?php
                        $url = "";
                        //Si no esta retirado tendrá enlaces
                        if($item->retirado_asigl0 =='N' && $item->fac_hces1 != 'D' && $item->fac_hces1 != 'R'){  
                            $webfriend = !empty($item->webfriend_hces1)? $item->webfriend_hces1 :  str_slug($item->titulo_hces1);
                            if($data['type'] == "theme"){
                                $url_vars = "?theme=".$data['theme'];
                            }else{
                                $url_vars ="";
                            }
                            $url_friendly = \Tools::url_lot($item->cod_sub,$item->id_auc_sessions,$item->name,$item->ref_asigl0,$item->num_hces1,$item->webfriend_hces1,$item->titulo_hces1);
                            $url = "href='$url_friendly'";                                
                        }
                         
                         //galileo será solo titulio y descweb
                         $titulo ="$item->ref_asigl0  -  $item->descweb_hces1";
                        /*
                        $titulo ="";
                        if(\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
                            $titulo ="$item->ref_asigl0  -  $item->titulo_hces1";
                        }elseif(!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
                            $titulo = $item->titulo_hces1;        
                        }elseif(\Config::get('app.ref_asigl0')){
                            $titulo = trans(\Config::get('app.theme').'-app.lot.lot-name') ." ".$item->ref_asigl0 ;
                        }
                        */
                        $precio_venta=NULL;
                        if (!empty($item->himp_csub)){
                                $precio_venta=$item->himp_csub;
                        }
                        //si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
                        elseif($item->subc_sub == 'H' && $item->cod_sub == $item->sub_hces1 && $item->lic_hces1 == 'S' and $item->implic_hces1 >0){
                                $precio_venta = $item->implic_hces1;
                        }
                        
                        $winner = "";
                        //si el usuario actual es el
                        if(isset($data['js_item']['user']) && count($item->ordenes) > 0 && head($item->ordenes)->cod_licit == $data['js_item']['user']['cod_licit']){
                            $winner = "winner";
                        }
                        //si hay usuario conectado pero no es el ganador.
                        elseif(isset($data['js_item']['user'])){
                            $winner = "no_winner";
                        }
                        
                        $img = Tools::url_img('lote_medium',$item->num_hces1,$item->lin_hces1);
                            
                        
                        $class_square = 'col-xs-12 col-sm-6 col-lg-4';
                    ?>
                    @include('includes.lotlist')
                    <?php 
                        $class_square = 'col-xs-12';
                    ?>
                    @include('includes.lotlist_large')
                    <?php 
                        $class_square = 'col-xs-4 col-sm-3 col-md-2';
                    ?>
                    @include('includes.lotlist_mini')
                @endforeach
                
            </div>
        </div>
        <div class="col-xs-12 col-md-8 col-md-offset-3 col-xs-offset-0">
            <?php echo $data['subastas.paginator']; ?>
        </div>
    </div>
</div>

@if(!empty($data['seo']->meta_content) && $data['subastas.paginator']->currentPage == 1)
<div class="container category">
	<div class="row">
		<div class="col-lg-12">
                <?= $data['seo']->meta_content?>
                </div>
        </div>
</div>
@endif

</form>