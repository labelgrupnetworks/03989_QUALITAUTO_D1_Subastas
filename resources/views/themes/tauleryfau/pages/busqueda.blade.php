@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')

<section class="body-auctions2">

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 resultok">
                <h1 class="titlePage">{{ trans($theme.'-app.subastas.search-results') }} - {{$data['search']}}</h1>
        </div>
        <div class="col-xs-12">
            <div class="onlyHistoric">
                <form id="formsearch" role="search" action="{{ \Routing::slug('busqueda') }}" class="" method="get">
                    <div class="search-button-content flex">
                        <input class="form-control input-custom" placeholder="{{ trans($theme.'-app.head.search_label') }}" type="text" name="texto" id="textSearch" value="{{$data['search']}}">
                        <button type="submit" class="btn">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                    <div class="switch_content">
                        <label class="switcher <?= ($data['history'] == 'H')?'switcher-active':'';?>" for="onlyHistoric"><small></small></label>
                        <small>{{ trans($theme.'-app.head.search_historic') }}</small>
                        <input type="checkbox" name="history" class="js-switch submit_on_change" id="onlyHistoric" style="display:none" value="H" <?= ($data['history'] == 'H')?'checked':'';?>/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container">
    @if(count($data['subastas']) == 0 )
            <div class="row">
                <div  class="alert alert-danger" >{{trans($theme.'-app.subastas.search-no_results')}}</div>
            </div>
        @endif
    <div class="row">
        <div class="col-xs-12">
                @if (Config::get('app.group_auction_in_search'))
                <div class="search_list_lot">
                        @foreach ($data['subastas'] as $key => $item)
                        <?php
                                        $url_lotes=\Routing::translateSeo('subasta').$item->cod_sub."-".str_slug($item->name)."-".$item->id_auc_sessions;
                                    ?>
                        <div class="col-xs-12 col-sm-6 col-md-3">
                            <div class="bid bid_search">
                                <div class="bid-wrapper">
                                    <div class="lot-img flex valign">
                                        <a title="{{ $item->name }}" href="{{$url_lotes}}?description={{$data['search']}}">
                                            <img
                                            src="/img/load/subasta_medium/AUCTION_{{ Config::get('app.emp')}}_{{ $item->cod_sub }}.jpg"
                                            alt="{{ $item->name }}"
                                            class="img-responsive"
                                            width="100%;"
                                                />
                                        </a>
                                    </div>
                                    <div class="btn-data-wrapper">
                                        <h4 class="text-center">{{ $item->name }}</h4>
                                        <div class="btn-group">
                                            <div class="btn-wiew-lot btn-pujar">
                                                <a title="{{ $item->name }}" href="{{ $url_lotes }}" class="btn btn-custom">{{ trans($theme.'-app.subastas.see_lotes') }}</a>
                                            </div>
                                            <div class="btn-info-bid">
                                                <a title="{{ $item->name }}" class="flex valign btn-color">{{$item->cuantos}} {{trans($theme.'-app.lot_list.lots')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

                            $titulo ="";
                            if(\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
                                    $titulo ="$item->ref_asigl0  -  $item->titulo_hces1";
                            }elseif(!\Config::get('app.ref_asigl0') && \Config::get('app.titulo_hces1')){
                                $titulo = $item->titulo_hces1;
                            }elseif(\Config::get('app.ref_asigl0')){
                                $titulo = trans($theme.'-app.lot.lot-name') ." ".$item->ref_asigl0 ;
                            }

                            $precio_venta=NULL;
                            if (!empty($item->himp_csub)){
                                $precio_venta=$item->himp_csub;
                            }
                            //si es un histórico y la subasta del asigl0 = a la del hces1 es que no está en otra subasta y podemso coger su valor de compra de implic_hces1
                            elseif($item->subc_sub == 'H' && $item->cod_sub == $item->sub_hces1 && $item->lic_hces1 == 'S' and $item->implic_hces1 >0){
                                $precio_venta = $item->implic_hces1;
                            }
                            //en el buscador no miraremos si el usuario actual es el ganador de la subasta ya que hay varias subastas y por cada una tiene un códig ode licitador
                            $winner = "";

                            ?>
                        @include('includes.lotlist')
                    @endforeach

                @endif
        </div>

        @if(isset($data['subastas.paginator']))
            <?= $data['subastas.paginator'] ?>
        @endif
        </div>
    </div>

</div>
</section>
<script>
    see_img();
</script>




@stop
