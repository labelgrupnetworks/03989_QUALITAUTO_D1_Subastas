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

<form id="pagar_lotes">
<div class="container panel">
	<div class="row">
		<div class="col-xs-12 col-sm-12">

		<div class="content-tabs-height">
        <?php $tab="allotments"; ?>
	@include('pages.panel.menu_micuenta')
		  <div class="tab-content" style="margin-top: 20px">
		    <div role="tabpanel" class="tab-pane tabe-cust active" id="dos">
                        <div class="tabs">
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li role="pagar" class="active"><a href="{{ \Routing::slug('user/panel/allotments/outstanding') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.still_paid') }}</a></li>
                                <li role="pagadas" ><a href="{{ \Routing::slug('user/panel/allotments/paid') }}" >{{ trans(\Config::get('app.theme').'-app.user_panel.bills') }}</a></li>
                            </ul>
                        </div>

                        <div class="tab-content" >
                            <div role="tabpanel" class="tab-pane active" id="home">
                                <div class="table-responsive">
				<table class="table table-striped table-custom table-mi-ad">
					<thead>
						<tr>
                                                    @if( !empty(\Config::get( 'app.pasarela_web' )))
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.choose_lot') }}</th>
                                                    @endif
                                                        <th></th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.lot') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.auction') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.name') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.price') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.price_clean') }}</th>
                                                        <th>{{ trans(\Config::get('app.theme').'-app.user_panel.date') }}</th>
						</tr>
					</thead>
					<tbody>
                                            <!-- Falta de una orden -->
                                            <?php $i=0 ?>
                                            @foreach($data['adjudicaciones'] as $puj)
                                             <?php
                                                $url_friendly = str_slug($puj->titulo_hces1);
                                                $url_friendly = \Routing::translateSeo('lote').$puj->cod_sub."-".str_slug($puj->name).'-'.$puj->id_auc_sessions."/".$puj->ref_asigl0.'-'.$puj->num_hces1.'-'.$url_friendly;
                                                $precio_remapte = \Tools::moneyFormat($puj->himp_csub);
                                                $precio_limpio = \Tools::moneyFormat($puj->himp_csub + $puj->base_csub + $puj->base_csub_iva,false,2);
                                                $precio_limpio_calculo =  number_format($puj->himp_csub + $puj->base_csub + $puj->base_csub_iva, 2, '.', '');
                                            ?>

                                                <tr class="<?php if($i%2==0){
                                                        echo('background-color-grey');

                                                    }else{
                                                        echo('background-color-white');

                                                        } ?>">
                                                    @if( !empty(\Config::get( 'app.pasarela_web' )))
                                                        <td><span class="text-center"><input type="checkbox" id="{{$i}}" class="add-carrito form-control" name="carrito[{{ $puj->sub_csub }}][{{$puj->ref_csub}}][pagar]" price="<?= $precio_limpio_calculo ?>"></span></td>
                                                    @endif
                                                    <td onclick="window.location='{{$url_friendly}}'"><img src="/img/load/lote_small/{{ $puj->imagen }}" height="42"></td>
                                                        <td onclick="window.location='{{$url_friendly}}'">{{$puj->ref_asigl1}}</td>
                                                       @if(strtoupper($puj->tipo_sub) == 'O' || strtoupper($puj->tipo_sub) == 'P')
                                                           <td onclick="window.location='{{$url_friendly}}'">{{ trans(\Config::get('app.theme').'-app.user_panel.auctions_online') }}</td>
                                                        @else
                                                           <td onclick="window.location='{{$url_friendly}}'">{{$puj->cod_sub}}</td>
                                                        @endif
                                                        <td onclick="window.location='{{$url_friendly}}'">{{ $puj->titulo_hces1}}</td>
                                                        <td onclick="window.location='{{$url_friendly}}'"><?= $precio_remapte ?> €</td>
                                                        <td onclick="window.location='{{$url_friendly}}'"><?=  $precio_limpio ?> €</td>
							<td onclick="window.location='{{$url_friendly}}'"> {{ $puj->date }} </td>
                                                </tr>
                                                <tr style="display: none;" class="toggle-{{$i}}
                                                    <?php if($i%2==0){
                                                        echo('background-color-grey');

                                                    }else{
                                                        echo('background-color-white');

                                                        } ?>
                                                ">
                                                    <td colspan="4">
														<input class="hide" type="hidden" name="carrito[{{$puj->sub_csub}}][{{$puj->ref_csub}}][envios]" value='1'>
                                                    </td>
                                                    @if(1!=1)
                                                        <td colspan="4" >
                                                            <div class="seguros">
                                                                <div class="tit_mi">Opciones adicionales</div>
                                                                @if($puj->transport_hces1 == 'S')
                                                                    <div class="radio">
                                                                        <label>
                                                                    <span>
                                                                        <input class="seguro " type="radio" name="seguro[{{$i}}]"  value="10">Seguro Si<br>
                                                                        </label>
                                                                    </div>
                                                                    <div class="radio">
                                                                        <label>
                                                                            <input class="seguro" type="radio" name="seguro[{{$i}}]" checked="" value="0">Seguro No
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                                <?php $i++ ?>
                                             @endforeach
					</tbody>
				</table>
                                </div>
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
    @if( !empty(\Config::get( 'app.pasarela_web' )))
        <div class="row-fluid">
            <div class="col-xs-12 col-sm-6 adj">
                 <h1 class="titlecat"><span class="precio_final">0</span> €</h1>
                <button type="button" id="submit_carrito" class="btn btn-step-reg" disabled>Pagar</button>
            </div>
            @if (1 == 2)
            <div class="col-xs-12 col-sm-6 contact-background">
                <div class="row-fluid">
                    <div class="tit_mi">{{ trans(\Config::get('app.theme').'-app.user_panel.address_send') }}</div>

                    <div class="col-sm-6">
                        <p class="mb0"><?= !empty($data['envio']->dir_clid)?$data['envio']->dir_clid : '';?></p>
                        <p class="mb0"><?= !empty($data['envio']->pob_clid)?$data['envio']->pob_clid:'' ?> <?= !empty($data['envio']->cp_clid)? $data['envio']->cp_clid:'';?></p>
                        <p class="mb0"><?= !empty($data['envio']->pais->des_paises)?$data['envio']->pais->des_paises:'';?>, <?= !empty($data['envio']->codpais_clid)?$data['envio']->codpais_clid:'';?></p>
                        <a title="{{ trans(\Config::get('app.theme').'-app.user_panel.modify_address') }}" class="btn btn-de" href="{{ \Routing::slug('user/panel/info') }}">{{ trans(\Config::get('app.theme').'-app.user_panel.modify_address') }}</a>
                    </div>
                    <div class="col-sm-6">
                       <p><i class="fa fa-user" aria-hidden="true"></i> {{$data['user']->rsoc_cli}}</p>
                       <p><i class="fa fa-phone" aria-hidden="true"></i> {{$data['user']->tel1_cli}} </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @endif


</div>
    <style>
        .table > tbody > tr > td{
           vertical-align: inherit;
        }
    </style>
</form>
@stop
