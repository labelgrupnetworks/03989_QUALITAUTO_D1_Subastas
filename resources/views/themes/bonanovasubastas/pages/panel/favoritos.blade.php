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
                                <h4 class="panel-title">
                                  <a data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}">{{$all_inf['inf']->name}}</a>
                                </h4>
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
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</th>
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
                                                    <td onclick="window.location='{{$url_friendly}}'"><img src="{{ \Tools::url_img("lote_small", $inf_lot->num_hces1, $inf_lot->lin_hces1) }}" height="42"></td>
                                                    <td onclick="window.location='{{$url_friendly}}'"> {{$inf_lot->ref_asigl0}}</td>
                                                    @if(strtoupper($inf_lot->tipo_sub) == 'O' || strtoupper($inf_lot->tipo_sub) == 'P')
                                                        <td onclick="window.location='{{$url_friendly}}'">{{ trans(\Config::get('app.theme').'-app.user_panel.auctions_online') }}</td>
                                                    @else
                                                        <td onclick="window.location='{{$url_friendly}}'">{{$inf_lot->cod_sub}}</td>
                                                    @endif
                                                    <td onclick="window.location='{{$url_friendly}}'">{{$inf_lot->titulo_hces1}}</td>
                                                    <td onclick="window.location='{{$url_friendly}}'"><?= empty($inf_lot->pujas->formatted_imp_asigl1)? '-': $inf_lot->pujas->formatted_imp_asigl1.' €' ; ?></td>
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

