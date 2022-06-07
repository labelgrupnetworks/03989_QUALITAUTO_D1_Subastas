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
<div id="prova2">
</div>
<div class="container panel">
	<div class="row">
		<div class="col-xs-12 col-sm-12">

		<div class="">
        <?php $tab="orders"; ?>
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
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.mi_puja') }}</th>
                                                    </tr>
						</tr>
					</thead>
					<tbody>
                                            <!-- Falta de una orden -->
                                            @foreach($data['values'] as $puj)
                                                         <?php

                                                            $url_friendly = str_slug($puj->titulo_hces1);
                                                            $url_friendly = \Routing::translateSeo('lote').$puj->cod_sub."-".str_slug($puj->session_name).'-'.$puj->id_auc_sessions."/".$puj->ref_asigl0.'-'.$puj->num_hces1.'-'.$url_friendly;

                                                        ?>
                                                    <tr onclick="window.location='{{$url_friendly}}'">
                                                        <td scope="row"><img src="{{ \Tools::url_img("lote_small", $puj->num_hces1, $puj->lin_hces1) }}" height="42"></td>
                                                        <td>{{$puj->ref_asigl0}}</td>
                                                        @if(strtoupper($puj->tipo_sub) == 'O' || strtoupper($puj->tipo_sub) == 'P')
                                                           <td>{{ trans(\Config::get('app.theme').'-app.user_panel.auctions_online') }}</td>
                                                        @else
                                                           <td>{{$puj->cod_sub}}</td>
                                                        @endif
                                                        <td>{{ $puj->titulo_hces1}}</td>
							<td>{{$puj->date}} </td>
							<td> {{$puj->formatted_imp }} €</td>
                                                    </tr>
                                             @endforeach

					</tbody>
				</table>

			</div>
                        <?php echo $data['paginator']; ?>
                        <div class="loading-page">
                            <div class="spinner">
                                <div class="double-bounce1"></div>
                                <div class="double-bounce2"></div>
                            </div>
                        </div>
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
