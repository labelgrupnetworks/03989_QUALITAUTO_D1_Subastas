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
            <?php $tab="orders";?>               
            @include('pages.panel.menu_micuenta')
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            @foreach($data['values'] as $key_sub => $all_inf)
                              <div class="panel-heading">
                                <a data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">
                                    <h4 class="panel-title">
                                        {{$all_inf['inf']->name}}
                                    </h4>
                                </a>
                              </div>
                              <div id="{{$all_inf['inf']->cod_sub}}" class="panel-collapse collapse <?= count($data['values']) == '1'? 'in':' ';?>">
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-custom">
                                            <thead>
                                                    <tr>
                                                        <th> </th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.auction') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</th>
                                                    </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($all_inf['lotes'] as $inf_lot)
                                                    <?php
                                                        $url_friendly = str_slug($inf_lot->titulo_hces1);
                                                        $url_friendly = \Routing::translateSeo('lote').$inf_lot->cod_sub."-".str_slug($inf_lot->session_name).'-'.$inf_lot->id_auc_sessions."/".$inf_lot->ref_asigl0.'-'.$inf_lot->num_hces1.'-'.$url_friendly;
                                                    ?>
                                                    <tr>
                                                        <td><img src="/img/load/lote_small/{{ $inf_lot->imagen }}" height="42"></td>
                                                        <td>{{$inf_lot->ref_asigl0}}</td>
                                                        @if(strtoupper($inf_lot->tipo_sub) == 'O' || strtoupper($inf_lot->tipo_sub) == 'P')
                                                            <td>{{ trans(\Config::get('app.theme').'-app.user_panel.auctions_online') }}</td>
                                                        @else
                                                            <td>{{$inf_lot->cod_sub}}</td>
                                                        @endif
                                                        <td>{{$inf_lot->titulo_hces1}}</td>
                                                        <td>{{$inf_lot->date}}</td>
                                                        <td>{{$inf_lot->formatted_imp }} €</td>
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
