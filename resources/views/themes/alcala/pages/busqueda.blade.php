@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')
<div class="container color-letter">
        <div class="row">
            <div class="col-xs-12 col-sm-12 text-center color-letter">
                    <h1 class="titlePage search-title">{{ trans($theme.'-app.subastas.search-results') }} - {{$data['search']}}</h1>
            </div>
        </div>
    </div>
<div class="search content-page color-letter">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="onlyHistoric col-xs-12 no-padding">
                        <div class="onlyHistoric ">
                            <form id="formsearch" role="search" action="{{ \Routing::slug('busqueda') }}" class="" method="get">
                                <br><br>
                                <div class="row">
                                    <div class="col-xs-12 col-md-6 text-center">
                                        <div class="switch_content">
                                            <label class="switcher <?= ($data['history'] == 'H')?'switcher-active':'';?>" for="onlyHistoric"><small></small></label>
                                            <small>{{ trans($theme.'-app.head.search_historic') }}</small>
                                            <input type="checkbox" name="history" class="js-switch submit_on_change" id="onlyHistoric" style="display:none" value="H" <?= ($data['history'] == 'H')?'checked':'';?>/>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="inputs-custom-group">
                                        
                                            <input class="form-control" placeholder="{{ trans($theme.'-app.head.search_label') }}" type="text" name="texto" id="textSearch" value="{{$data['search']}}">
                                            <button type="submit" class="secondary-button"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <br><br>
                            </form>
            
            </div>
                     
                        
                    </div>
                    </div>
            <div class="col-xs-8 col-xs-offset-2">
                    @if(count($data['subastas']) == 0 )
                    <div class="row">
                        <div  class="alert alert-danger" >{{trans($theme.'-app.subastas.search-no_results')}}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container">
	<div class="row">

    </div>
</div>
<div class="container mb-25">
    
    <div class="row">
        <div class="col-xs-12">

            <div class="list_lot">
                @if (Config::get('app.group_auction_in_search'))
                <div class="search_list_lot col-xs-12 no-padding">
                        @foreach ($data['subastas'] as $key => $item)
                        <?php
                                        $url_lotes=\Routing::translateSeo('subasta').$item->cod_sub."-".str_slug($item->name)."-".$item->id_auc_sessions;
                                    ?> 
                        <div class="col-xs-12 col-sm-4 col-md-3 pb-15">
                                <a href="{{$url_lotes}}?description={{$data['search']}}" class="color-letter" target="_blank" >

                            <div class="list_lot_search">
                                <div class="list_lot_search_content d-flex ">
                                    <div class="img_search_lot">
                                        <img src="{{\Tools::url_img_session('subasta_medium',$item->cod_sub,$item->reference)}}" class="img-responsive" style="width: 100%;" />

                                    </div>
                                     <p class="num_lot">
                                                @if ($item->cuantos == '1')
                                                    {{$item->cuantos}} {{trans($theme.'-app.lot_list.reference')}}
                                                @else
                                                    {{$item->cuantos}} {{trans($theme.'-app.lot_list.lots')}}
                                                @endif
                                            </p> 
                                    <div class="caption">
                                        
                                        <p>{{$item->name}}</p>
                                       

                                    </div>
                                   
                                </div>
                            </div>
                        </a>
                            
                        </div>
                    @endforeach
                </div>
                @else
                    @foreach ($data['subastas'] as $key => $item)
                        <?php
                          $url = "";
                            //Si no esta retirado tendrá enlaces
                            if($item->retirado_asigl0 =='N'){  
                                $url_friendly = str_slug($item->webfriend_hces1);
                                $url_friendly = \Routing::translateSeo('lote').$item->cod_sub."-".str_slug($item->name).'-'.$item->id_auc_sessions."/".$item->ref_asigl0.'-'.$item->num_hces1.'-'.$url_friendly;
                                 $url = "href='$url_friendly'";    
                            }

                            $class_square = 'col-xs-12 col-sm-3';

                                                       
                            $titulo = $item->ref_asigl0.'.  '.strip_tags($item->descweb_hces1);
                            $cerrado = $item->cerrado_asigl0 == 'S'? true : false;    
                            $hay_pujas = !empty($item->max_puja)? true : false;
                            $devuelto= $item->cerrado_asigl0 == 'D'? true : false;
                            $remate = $item->remate_asigl0 =='S'? true : false;
                            $subasta_online = ($item->tipo_sub == 'P' || $item->tipo_sub == 'O')? true : false; 
                            $subasta_venta = $item->tipo_sub == 'V' ? true : false; 
                            $subasta_web = $item->tipo_sub == 'W' ? true : false; 
                            $subasta_abierta_O = $item->subabierta_sub == 'O'? true : false;
                            $subasta_abierta_P = $item->subabierta_sub == 'P'? true : false;
                            $retirado = $item->retirado_asigl0 !='N'? true : false;
                            $sub_historica = $item->subc_sub == 'H'? true : false;
                            $remate = $item->remate_asigl0 =='S'? true : false;
                            $awarded = \Config::get('app.awarded');
                            // D = factura devuelta, R = factura pedniente de devolver
                            $fact_devuelta = ($item->fac_hces1 == 'D' || $item->fac_hces1 == 'R') ? true : false;
                            

                            $precio_venta=NULL;
                            if (!empty($item->himp_csub)){
                                $precio_venta=$item->himp_csub;
                            }
                            //si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
                            elseif($item->subc_sub == 'H' && $item->cod_sub == $item->sub_hces1 && $item->lic_hces1 == 'S' and $item->implic_hces1 >0){
                                $precio_venta = $item->implic_hces1;
                            }
                            //si tiene preci ode venta o se ha marado como devuelto
                            $vendido = (!empty($precio_venta)|| $item->desadju_asigl0 =='S' )? true : false;
                            //en el buscador no miraremos si el usuario actual es el ganador de la subasta ya que hay varias subastas y por cada una tiene un códig ode licitador
                            $winner = "";
                            $img = Tools::url_img('lote_medium',$item->num_hces1,$item->lin_hces1);
                            ?> 
                        @include('includes.lotlist')
                    @endforeach
                    
                @endif
            </div>
        </div>
        
        @if(isset($data['subastas.paginator']))
            <?= $data['subastas.paginator'] ?>
        @endif
        
    </div>
    
</div>
	
<script>
    see_img();    
</script>

			


@stop
