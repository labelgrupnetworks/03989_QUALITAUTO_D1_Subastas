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
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<h1 class="titlePage">{{ trans(\Config::get('app.theme').'-app.user_panel.mi_cuenta') }}</h1>
		</div>
	</div>
</div>

<div class="container panel">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="" >
                <?php $tab="allotments";?>
                    @include('pages.panel.menu_micuenta')
                    <div class="tab-content" style="margin-top: 20px;">
                        <div role="tabpanel" class="tab-pane tabe-cust active" id="dos">
                            <div class="tabs">
                                <ul class="nav nav-tabs nav-justified" role="tablist">
                                    <li role="pagar" ><a href="{{ \Routing::slug('user/panel/allotments/outstanding') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.still_paid') }}</a></li>
                                    <li role="pagadas" class="active" ><a href="{{ \Routing::slug('user/panel/allotments/paid') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.bills') }}</a></li>
                            </ul>
                            </div>
                            <div class="tab-content" >
                                <div role="tabpanel" class="tab-pane active">
                                    <div class="table-responsive">
                                        <div class="panel-group" id="accordion">
                                            <div class="panel panel-default">
                                                @foreach($all_adj as $key_sub => $all_inf)
                                                    <div class="panel-heading">
                                                        <a data-toggle="collapse" href="#{{$all_inf['inf']->cod_sub}}" style="position:relative; background: lightgrey;color: #101010">
                                                            <h4 class="panel-title">
                                                                {{$all_inf['inf']->name}}
                                                            </h4>
                                                        </a>
                                                    </div>
                                                    <div id="{{$all_inf['inf']->cod_sub}}" class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped table-custom">
                                                                    <thead>
                                                                        <tr>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th class="text-center">{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
                                                                                <th>{{ trans(\Config::get('app.theme').'-app.user_panel.auction') }}</th>
                                                                                <th>{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}</th>
                                                                                <th class="text-right">{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</th>
                                                                                <th class="text-center">{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($all_inf['lotes'] as $puj)
                                                                            <?php
                                                                                $url_friendly = str_slug($puj->titulo_hces1);
                                                                                $url_friendly = \Routing::translateSeo('lote').$puj->cod_sub."-".str_slug($puj->name).'-'.$puj->id_auc_sessions."/".$puj->ref_asigl0.'-'.$puj->num_hces1.'-'.$url_friendly;

                                                                                $precio_remapte = \Tools::moneyFormat($puj->himp_csub);
                                                                                $precio_limpio = \Tools::moneyFormat($puj->himp_csub + $puj->base_csub + $puj->base_csub_iva,false,2);
                                                                                $precio_limpio_calculo =  number_format($puj->himp_csub + $puj->base_csub + $puj->base_csub_iva, 2, '.', '');
                                                                            ?>
                                                                            <tr onclick="window.location='{{$url_friendly}}'">
                                                                                <td scope="row"><img src="{{ \Tools::url_img("lote_small", $puj->num_hces1, $puj->lin_hces1) }}" height="42"></td>
                                                                                <td class="text-center" style="padding-right:10px;">{{$puj->ref_asigl1}}</td>
                                                                                <td style="padding-left:10px;">{{$puj->cod_sub}}</td>
                                                                                <td style="padding-left:10px;">{{ $puj->titulo_hces1}}</td>
                                                                                <td style="padding-right:10px;text-align:right;"><?= $precio_remapte ?> €</td>
                                                                                <td style="text-align:center">{{$puj->date}} </td>
                                                                                <td>
                                                                                @if(!empty($puj->pending_fact) && $puj->fac_csub == 'S')
                                                                                    Pendiente de pagar
                                                                                @elseif($puj->fac_csub == 'N' && $puj->estado_csub0 == 'C')
                                                                                    Prefacturado
                                                                                @else
                                                                                    Facturado
                                                                                @endif
                                                                                </td>
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
                        </div>
                    </div>
		</div>
            </div>
	</div>
    </div>

@stop
