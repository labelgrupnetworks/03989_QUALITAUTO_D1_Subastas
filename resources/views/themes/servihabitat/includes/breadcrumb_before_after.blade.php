
		<div class="col-xs-12 no-padding" >
			<div class="col-xs-12 col-sm-7 no-padding bread-after">
				<div class="bread" >
					@include('includes.bread')
				</div>
			</div>

@php

	/* Anterior y siguiente en base a todos los lotes visibles    */
	$fgasigl0 = new App\Models\V5\FgAsigl0();
	/* MOSTRAR SOLO LAS SUBASTAS QUE PUEDE VER EL USUARIO */
	if(\Config::get("app.restrictVisibility")){
				$fgasigl0 = $fgasigl0->Visibilidadsubastas(\Session::get('user.cod'));
	}

	$lots =  $fgasigl0->select("ref_asigl0, sub_asigl0")->ActiveLotForCategory()->ActiveLotAsigl0()
			->orderby("FGASIGL0.FFIN_ASIGL0", "DESC")->orderby("FGASIGL0.HFIN_ASIGL0", "DESC")->orderby("FGASIGL0.REF_ASIGL0","ASC")->get();
	$anterior = null;
	$actual = null;
	$siguiente = null;
	foreach($lots as $lot){
		if(!empty($actual)){
			$siguiente = $lot;
			break;
		}
		if($lot->sub_asigl0 == $lote_actual->cod_sub && $lot->ref_asigl0 == $lote_actual->ref_asigl0 ){
			$actual = $lot;
		}else{
			$anterior = $lot;
		}
	}

	if(!empty($anterior) || !empty($siguiente)){
		$where = " ( ";
		$or="";

		if(!empty($anterior)){
			$where .= " (sub_asigl0 = '".$anterior->sub_asigl0."' and ref_asigl0 =". $anterior->ref_asigl0." )";
			$or = " OR ";
		}
		if(!empty($siguiente)){
			$where .= $or." (sub_asigl0 = '".$siguiente->sub_asigl0."' and ref_asigl0 =". $siguiente->ref_asigl0." )";
		}

		$where .= " ) ";


		$fgasigl0 = new App\Models\V5\FgAsigl0();
		$lotsAntSig = $fgasigl0->GetLotsByRefAsigl0($where)->get();
		$urlAnterior ="";
		$urlSiguiente ="";
		foreach ($lotsAntSig as $item){

			$url_friendly = \Tools::url_lot($item->cod_sub,$item->id_auc_sessions,$item->name,$item->ref_asigl0,$item->num_hces1,$item->webfriend_hces1,$item->descweb_hces1);


			if( !empty($anterior) && $anterior->sub_asigl0 == $item->sub_asigl0  && $anterior->ref_asigl0 == $item->ref_asigl0 ){
				$urlAnterior = $url_friendly;
			}elseif(!empty($siguiente) && $siguiente->sub_asigl0 == $item->sub_asigl0  && $siguiente->ref_asigl0 == $item->ref_asigl0 ){
				$urlSiguiente = $url_friendly;
			}

		}
	}


@endphp




			<div class="col-xs-12 col-sm-5 no-padding follow">
				<div class="next  align-item-center @if(!empty($urlAnterior)) d-flex justify-content-space-between @endif text-right">
					@if(!empty($urlAnterior))
						<a class="color-letter nextLeft" title="{{ trans(\Config::get('app.theme').'-app.subastas.last') }}" href="{{$urlAnterior}}"><i class="fa fa-angle-left fa-angle-custom"></i> {{ trans(\Config::get('app.theme').'-app.subastas.last') }}</a>
					@endif


					@if(!empty($urlSiguiente))
						<a class="color-letter nextRight" title="{{ trans(\Config::get('app.theme').'-app.subastas.next') }}" href="{{$urlSiguiente}}">{{ trans(\Config::get('app.theme').'-app.subastas.next') }} <i class="fa fa-angle-right fa-angle-custom"></i></a>
					@endif


				</div>
			</div>

		</div>
		@if(\Config::get("app.exchange"))
			<div class="col-xs-12 text-right">
				{{ trans(\Config::get('app.theme').'-app.lot.foreignCurrencies') }}
					<select id="currencyExchange">
						@foreach($data['divisas'] as $divisa)

								<?php //quieren que salgan los dolares por defecto (sin no hay nada o hay euros  ?>
								<option value='{{ $divisa->cod_div }}' <?= ($data['js_item']['subasta']['cod_div_cli'] == $divisa->cod_div || ($divisa->cod_div == 'USD' &&  ($data['js_item']['subasta']['cod_div_cli'] == 'EUR'  || $data['js_item']['subasta']['cod_div_cli'] == '' )))? 'selected="selected"' : '' ?>>
									{{ $divisa->cod_div }}
								</option>

						@endforeach
					</select>

			</div>
		@endif
