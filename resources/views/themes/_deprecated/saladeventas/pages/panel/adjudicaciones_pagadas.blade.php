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

		<div class="">
        <?php $tab="allotments";?>               
	@include('pages.panel.menu_micuenta')
		  <div class="tab-content">
		    <div role="tabpanel" class="tab-pane tabe-cust active" id="dos">
                        <div class="tabs">
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                               <li role="pagar" class="active"><a href="{{ \Routing::slug('user/panel/allotments/outstanding') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.still_paid') }}</a></li>
                                <li role="pagadas" ><a href="{{ \Routing::slug('user/panel/allotments/paid') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.bills') }}</a></li>   
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active">
                                <div class="table-responsive">
				<table class="table table-striped table-custom">
					<thead>
						<tr>
                                                    <tr>
                                                        <th> </th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.auction') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</th>
                                                        <th></th>
                                                    </tr>
						</tr>
					</thead>
					<tbody>
                                            <!-- Falta de una orden -->
                                            @foreach($data['adjudicaciones'] as $puj)
                                            <?php
                                                $url_friendly = str_slug($puj->titulo_hces1);
                                                $url_friendly = \Routing::translateSeo('lote').$puj->cod_sub."-".str_slug($puj->name).'-'.$puj->id_auc_sessions."/".$puj->ref_asigl0.'-'.$puj->num_hces1.'-'.$url_friendly;
                                                
                                                $precio_remapte = \Tools::moneyFormat($puj->himp_csub);
                                                $precio_limpio = \Tools::moneyFormat($puj->himp_csub + $puj->base_csub + $puj->base_csub_iva,false,2);
                                                $precio_limpio_calculo =  number_format($puj->himp_csub + $puj->base_csub + $puj->base_csub_iva, 2, '.', '');
                                            ?>
						<tr onclick="window.location='{{$url_friendly}}'">
                                                        <td scope="row"><img src="/img/load/lote_small/{{ $puj->imagen }}" height="42"></td>
                                                        <td>{{$puj->ref_asigl1}}</td>	
                                                        @if(strtoupper($puj->tipo_sub) == 'O' || strtoupper($puj->tipo_sub) == 'P')
                                                           <td>{{ trans(\Config::get('app.theme').'-app.user_panel.auctions_online') }}</td>
                                                        @else
                                                           <td>{{$puj->cod_sub}}</td>
                                                        @endif
                                                        <td>{{ $puj->titulo_hces1}}</td>
                                                        <td><?= $precio_remapte ?> €</td>
							<td>{{$puj->date}} </td>
                                                        <td>
                                                            @if($puj->fac_csub == 'S')
                                                             <th><a style="width: 100%" class="btn btn-del" target="_blank" href="/factura/{{$puj->afral_csub}}-{{$puj->nfral_csub}}">{{ trans(\Config::get('app.theme').'-app.user_panel.facturado') }}</a></th>
                                                            @else
                                                                <th><a style="width: 100%" class="btn btn-del" href="#">{{ trans(\Config::get('app.theme').'-app.user_panel.pendiente_facturar') }}</a></th>
                                                            @endif
                                                       
                                                </tr>
                                             @endforeach
					</tbody>
				</table>
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

		</div>

		</div>
	</div>
</div>

@stop
