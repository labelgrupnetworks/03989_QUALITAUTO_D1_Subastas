@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
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
                <?php $tab="orders";?> 
                @include('pages.panel.menu_micuenta')
            </div>
            <div class="col-xs-12 col-md-9 col-lg-9 ">
                <div class="user-account-title-content">
                    <div class="user-account-menu-title">{{ trans(\Config::get('app.theme').'-app.user_panel.orders') }}</div>
                </div>
                <div class="col-xs-12 no-padding ">
                    <div class="panel-group" id="accordion">
                            <div class="panel panel-default">
                                @foreach($data['values'] as $key_sub => $all_inf)
                                    <div class="panel-heading">
                                        <div class="panel-title">
                                          <a class="d-flex justify-content-space-between" data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">
                                            <span>{{$all_inf['inf']->name}}</span>
                                            <img width=10 src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI0cHgiIGhlaWdodD0iMjRweCIgdmlld0JveD0iMCAwIDk2LjE1NCA5Ni4xNTQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDk2LjE1NCA5Ni4xNTQ7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNMC41NjEsMjAuOTcxbDQ1Ljk1MSw1Ny42MDVjMC43NiwwLjk1MSwyLjM2NywwLjk1MSwzLjEyNywwbDQ1Ljk1Ni01Ny42MDljMC41NDctMC42ODksMC43MDktMS43MTYsMC40MTQtMi42MSAgIGMtMC4wNjEtMC4xODctMC4xMjktMC4zMy0wLjE4Ni0wLjQzN2MtMC4zNTEtMC42NS0xLjAyNS0xLjA1Ni0xLjc2NS0xLjA1NkgyLjA5M2MtMC43MzYsMC0xLjQxNCwwLjQwNS0xLjc2MiwxLjA1NiAgIGMtMC4wNTksMC4xMDktMC4xMjcsMC4yNTMtMC4xODQsMC40MjZDLTAuMTUsMTkuMjUxLDAuMDExLDIwLjI4LDAuNTYxLDIwLjk3MXoiIGZpbGw9IiMwMDAwMDAiLz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K" />
                                        </a>
                                        </div>
                                        <div id="{{$all_inf['inf']->cod_sub}}" class="panel-collapse collapse <?= count($data['values']) == '1'? 'in':' ';?>">

                                            <div class="user-account-heading hidden-xs d-flex align-items-center justify-content-space-between">
                                                <div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item">
                                                        {{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}
                                                </div>
                                                <div class="col-xs-12 col-sm-2 col-one user-account-fecha">
                                                        {{ trans(\Config::get('app.theme').'-app.user_panel.date') }}
                                                </div>
                                                <div class="col-xs-12 col-sm-3 col-lg-2 col-one user-account-max-bid">
                                                        {{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}
                                                </div>
                                            </div>
                                            <div class="user-accout-items-content">
                                                    @foreach($all_inf['lotes'] as $inf_lot)
                                                    <?php
                                                        $url_friendly = str_slug($inf_lot->titulo_hces1);
                                                        $url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->session_name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                                    ?>
                                                        <div class="user-accout-item-wrapper  col-xs-12 no-padding">
                                                            <div class="d-flex">
                                                            <div class="col-xs-12 col-sm-7 col-lg-8 col-one user-account-item ">
                                                                <div class="col-xs-12 col-sm-3 no-padding ">
                                                                    <img src="/img/load/lote_small/{{ $inf_lot->imagen }}" class="img-responsive">
                                                                </div>
                                                                <div class="col-xs-12 col-sm-8 col-sm-offset-1 no-padding">
                                                                        @if(strtoupper($inf_lot->tipo_sub) == 'O' || strtoupper($inf_lot->tipo_sub) == 'P')
                                                                            <div class="user-account-item-auction text-right"><small>{{ trans(\Config::get('app.theme').'-app.user_panel.auctions_online') }}</small></div>
                                                                        @endif
                                                                        <div class="user-account-item-title">{{$inf_lot->titulo_hces1}}</div>

                                                                        <div class="user-account-item-lot"><span>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }} {{$inf_lot->ref_asigl0}}</span></div>
                                                                        <div class="user-account-item-text"><div>{{$inf_lot->cod_sub}}</div></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-2 col-lg-2 account-item-border">
                                                                <div class="user-account-item-date d-flex align-items-center">
                                                                    <div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</div>
                                                                    {{$inf_lot->date}}
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-3 col-lg-2 account-item-border">
                                                                    <div class="user-account-item-price justify-content-center  d-flex align-items-center">
                                                                            <div class="visible-xs">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</div>
                                                                    <div><strong>{{$inf_lot->formatted_imp }} €</strong></div>
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
