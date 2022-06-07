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

		<div class="content-tabs-height">
        <?php $tab="favorites";?>
	@include('pages.panel.menu_micuenta')
		  <div class="tab-content">
		    <div role="tabpanel" class="tab-pane tabe-cust active" id="dos">
			<div class="table-responsive">
				<table class="table table-striped table-custom">
					<thead>
						<tr>
                                                    <tr>
                                                        <th> </th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.auction') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.max_pujas') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.n_pujas') }}</th>
                                                        <th></th>
                                                    </tr>
						</tr>
					</thead>
					<tbody>
                                            @foreach($data['favoritos'] as $puj)
                                                        <?php
                                                                $url_friendly = str_slug($puj->titulo_hces1);
                                                                $url_friendly = \Routing::translateSeo('lote').$puj->cod_sub."-".str_slug($puj->name).'-'.$puj->id_auc_sessions."/".$puj->ref_asigl0.'-'.$puj->num_hces1.'-'.$url_friendly;

                                                        ?>
						<tr class='{{$puj->ref_asigl0}}-{{$puj->cod_sub}}'>
                                                        <td scope="row" onclick="window.location='{{$url_friendly}}'" ><img src="{{ \Tools::url_img("lote_small", $puj->num_hces1, $puj->lin_hces1) }}" width="42"></td>
                                                        <td onclick="window.location='{{$url_friendly}}'">{{$puj->ref_asigl0}}</td>

                                                        @if(strtoupper($puj->tipo_sub) == 'O' || strtoupper($puj->tipo_sub) == 'P')
                                                           <td onclick="window.location='{{$url_friendly}}'">{{ trans(\Config::get('app.theme').'-app.user_panel.auctions_online') }}</td>
                                                        @else
                                                           <td onclick="window.location='{{$url_friendly}}'">{{$puj->cod_sub}}</td>
                                                        @endif
                                                        <td onclick="window.location='{{$url_friendly}}'">{{ $puj->titulo_hces1}}</td>
							<td onclick="window.location='{{$url_friendly}}'"> <?= empty($puj->pujas[0]->formatted_imp_asigl1)? '-': $puj->pujas[0]->formatted_imp_asigl1.' €' ; ?> </td>
							<td onclick="window.location='{{$url_friendly}}'"> {{ sizeof($puj->pujas)}}</td>
                                                        <td><a title="{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}" class="btn btn-del" href="javascript:action_fav_lote('remove','{{ $puj->ref_asigl0 }}','{{$puj->cod_sub }}',' <?php echo($data['codigos_licitador'][$puj->cod_sub]); ?>')">{{trans(\Config::get('app.theme').'-app.lot.del_from_fav')}}</a></td>
						</tr>
                                             @endforeach
					</tbody>
				</table>


			</div>

                        <?php echo $data['paginator']; ?>

		    </div>
		  </div>
                    <div class="loading-page">
                        <div class="spinner">
                            <div class="double-bounce1"></div>
                            <div class="double-bounce2"></div>
                        </div>
                        </div>

		</div>

		</div>
	</div>
</div>
@stop

