@foreach ($carcateristicasArray as $caracteristicasBlock)
<div class="row d-flex flex-wrap align-items-center">

	@foreach ($caracteristicasBlock as $idCarcateristica)

	@continue(empty($caracteristicas[$idCarcateristica]) || empty($caracteristicas[$idCarcateristica]->value_caracteristicas_hces1) || $caracteristicas[$idCarcateristica]->value_caracteristicas_hces1 == '0.0')

	@php
	$ruta = "/themes/$theme/assets/features/{$caracteristicas[$idCarcateristica]->id_caracteristicas}.png";

	//cambio
	if($idCarcateristica == 10){
		$caracteristicas[$idCarcateristica]->value_caracteristicas_hces1 = mb_convert_case(str_replace('Cambio ', '',$caracteristicas[$idCarcateristica]->value_caracteristicas_hces1), MB_CASE_TITLE);
	}

	@endphp
	<div class="col-xs-12 col-md-4 mb-2">

		<div class="features-wrapper row d-flex align-item-center">
			<div class="col-xs-2 col-sm-3 d-flex align-item-center p-0">
				<img class="img-responsive" src="{{$ruta}}" alt="">
			</div>
			<div class="col-xs-9 col-sm-7">
				<p class="m-0">{{$caracteristicas[$idCarcateristica]->name_caracteristicas}}</p>
				<p class="m-0">
					<strong>
						{{ is_numeric($caracteristicas[$idCarcateristica]->value_caracteristicas_hces1)
							? \Tools::moneyFormat($caracteristicas[$idCarcateristica]->value_caracteristicas_hces1)
							: $caracteristicas[$idCarcateristica]->value_caracteristicas_hces1
						}} {{ $unidades[$idCarcateristica] ?? '' }}
					</strong>
				</p>
			</div>
		</div>

	</div>
	@endforeach
</div>
@endforeach
