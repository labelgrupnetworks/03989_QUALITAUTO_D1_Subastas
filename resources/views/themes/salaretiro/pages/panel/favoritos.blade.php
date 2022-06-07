@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
		</div>
	</div>
</div>
<div class="container panel">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
            <?php $tab="favorites";?>               
            @include('pages.panel.menu_micuenta')
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            @foreach($data['favoritos'] as $key_sub => $all_inf)
                              <div class="panel-heading">
                                   <a data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">
                                <h4 class="panel-title">
                                 {{$all_inf['inf']->name}}
                                </h4>
                                   </a>
                              </div>
                              <div id="{{$all_inf['inf']->cod_sub}}" class="panel-collapse collapse <?= count($data['favoritos']) == '1'? 'in':' ';?>">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-custom">
                                            <thead>
                                                <tr>
                                                    <tr>
                                                        <th> </th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.auction') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</th>
                                                    </tr>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($all_inf['lotes'] as $inf_lot)
                                                <?php
                                                    $url_friendly = str_slug($inf_lot->titulo_hces1);
                                                    $url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                                ?>

                                                <tr class='{{$inf_lot->ref_asigl0}}-{{$inf_lot->cod_sub}}'>
                                                    <td><img src="/img/load/lote_small/{{ $inf_lot->imagen }}" height="42"></td>
                                                    <td>{{$inf_lot->ref_asigl0}}</td>
                                                    @if(strtoupper($inf_lot->tipo_sub) == 'O' || strtoupper($inf_lot->tipo_sub) == 'P')
                                                        <td>{{ trans(\Config::get('app.theme').'-app.user_panel.auctions_online') }}</td>
                                                    @else
                                                        <td>{{$inf_lot->cod_sub}}</td>
                                                    @endif
                                                    <td>{{$inf_lot->titulo_hces1}}</td>
                                                    <td><a title="{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}" class="btn btn-del" href="javascript:action_fav_lote('remove','{{ $inf_lot->ref_asigl0 }}','{{$inf_lot->cod_sub }}',' <?= $data['codigos_licitador'][$inf_lot->cod_sub] ?>')">{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}</a></td>
                                                </tr>
                                            @endforeach
                                           </tbody>
                                        </table>
                                    </div>
                                </div>
                              </div>
                            @endforeach
                        </div>
                    </div>
		</div>
	</div>
</div>
@stop

