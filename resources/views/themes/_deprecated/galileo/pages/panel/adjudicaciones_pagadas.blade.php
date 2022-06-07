@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<?php 
$all_adj= array();
$sub = new \App\Models\Subasta;
foreach($data['adjudicaciones'] as $temp_adj){
    $all_adj[$temp_adj->cod_sub]['lotes'][]=$temp_adj;
}
foreach($all_adj as $key_inf => $value){
    $sub->cod = $key_inf;
    $all_adj[$key_inf]['inf'] = $sub->getInfSubasta();
}

?>
<?php 
$fullname = Session::get('user.name');
$name = explode(",", $fullname);
?>
<div class="color-letter">
<div class="container">
    <div class="row">
        <div class="col-xs-12">
        <h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
        <small>{{  !empty($name[1]) ? $name[1] : ''}} {{ !empty($name[0]) ? $name[0] : '' }}</small>
        </div>
    </div>
</div>
</div>



    <div class="account-user color-letter  panel-user">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-3 col-lg-3 account-user-menu">
                        <?php $tab="allotments";?> 
                        @include('pages.panel.menu_micuenta')
                    </div>
                    <div class="col-xs-12 col-md-9 col-lg-9 ">
                        <div class="user-account-title-content">
                            <div class="user-account-menu-title">{{ trans(\Config::get('app.theme').'-app.user_panel.allotments') }}</div>
                        </div>
                        <div class="user-accounte-titles-link">
                            <ul class="ul-format d-flex justify-content-space-between flex-wrap" role="tablist">
                                <li role="pagar" ><a class="color-letter" href="{{ \Routing::slug('user/panel/allotments/outstanding') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.still_paid') }}</a></li>
                                <li role="pagadas" class="active" ><a class="color-letter" href="{{ \Routing::slug('user/panel/allotments/paid') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.bills') }}</a></li>   
                            </ul>
                        </div>
                        <div class="col-xs-12 no-padding ">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <?php $i=0 ?>
                                        @foreach($all_adj as $key_sub => $all_inf)
                                        <?php 
                                            $total_remate = 0;
                                            $total_base = 0;
                                            $total_iva = 0;
                                        ?>
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <a class="d-flex justify-content-space-between" data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">
                                                    <div>
                                                        <span>{{$all_inf['inf']->name}}</span>
                                                    </div>
                                                    <img width=10 src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
                                                    </a>
                                                </div>
                                                <div id="{{$all_inf['inf']->cod_sub}}"  class="table-responsive panel-collapse collapse in">
            
                                                    <div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
                                                        <div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item">
                                                                {{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
                                                        </div>
                                                        <div class="col-xs-12 col-sm-2 col-one user-account-fecha">
                                                                {{ trans(\Config::get('app.theme').'-app.user_panel.price') }}
                                                            
                                                        </div>
                                                        <div class="col-xs-12 col-sm-3 col-lg-2 col-one user-account-max-bid">
                                                                {{ trans(\Config::get('app.theme').'-app.user_panel.date') }}
                            
                                                        </div>
                                                    </div>
                                                    <div class="user-accout-items-content">
                                                           
                                                            @foreach($all_inf['lotes'] as $puj)
                                                            <?php
                                                            $url_friendly = str_slug($puj->titulo_hces1);
                                                            $url_friendly = \Routing::translateSeo('lote').$puj->cod_sub."-".str_slug($puj->name).'-'.$puj->id_auc_sessions."/".$puj->ref_asigl0.'-'.$puj->num_hces1.'-'.$url_friendly;

                                                            $precio_remapte = \Tools::moneyFormat($puj->himp_csub);
                                                            $precio_limpio = \Tools::moneyFormat($puj->himp_csub + $puj->base_csub + $puj->base_csub_iva,false,2);
                                                            $precio_limpio_calculo =  number_format($puj->himp_csub + $puj->base_csub + $puj->base_csub_iva, 2, '.', '');
                                                        ?>
                                                                <div class="user-accout-item-wrapper  col-xs-12 no-padding">
                                                                    <div class="d-flex">
                                                                    <div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item ">
                                                                        
                                                                        <div class="col-xs-12 col-sm-3 no-padding ">
                                                                            <img src="/img/load/lote_small/{{ $puj->imagen }}" class="img-responsive">
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-8 col-sm-offset-1 no-padding">
                                                                                @if(strtoupper($puj->tipo_sub) == 'O' || strtoupper($puj->tipo_sub) == 'P')
                                                                                    <div class="user-account-item-auction text-right"><small>{{ trans(\Config::get('app.theme').'-app.user_panel.auctions_online') }}</small></div>
                                                                                @endif
                                                                                <div class="user-account-item-title">{{ $puj->titulo_hces1}}</div>
            
                                                                                <div class="user-account-item-lot"><span>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }} {{ $puj->titulo_hces1}}</span></div>
                                                                                <div class="user-account-item-text"><div>{{$puj->cod_sub}}</div></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-2 col-lg-2 account-item-border">
                                                                        <div class="user-account-item-date d-flex flex-direction-column align-items-center justify-content-center">
                                                                            <div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</div>
                                                                            <p><?= $precio_remapte ?> €</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xs-12 col-sm-3 col-lg-2 account-item-border">
                                                                            <div class="user-account-item-price  d-flex align-items-center">
                                                                                
                                                                                    <div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</div>
                                                                            <div>{{$puj->date}}</div>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                </div>
            
                                                        @endforeach
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
    </div>



@stop
