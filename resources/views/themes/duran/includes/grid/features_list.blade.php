
<div id="js-filters-toogle-container2">
  @foreach($features as $idFeature => $feature)

	@if(!empty($featuresCount[$idFeature]))

	<div class="filters-auction-texts ">

		<span class="titulo-filtro">{{$feature}}</span>


			@php $featuresRequest  =request("features"); @endphp
			{{-- Si no han seleccionado esta caracteristica mostramos el combo --}}
			@if (empty($featuresRequest[$idFeature]))
				<select name="features[{{$idFeature}}]"  class="select_lot_list_js">
					<option value""> </option>
					@foreach($featuresCount[$idFeature] as $featureValue)
					<?php
						$selected = (!empty($featuresRequest[$idFeature])  && $featuresRequest[$idFeature] == $featureValue["id_caracteristicas_value"])? 'selected="selected"' :'';
					?>
						<option id="feature_{{$featureValue["id_caracteristicas_value"]}}" value="{{$featureValue["id_caracteristicas_value"]}}" {{$selected}}> {{$featureValue["value_caracteristicas_value"] }} ({{Tools::numberformat($featureValue["total"])}}) </option>
					@endforeach
				</select>
			{{-- si han seleccionado esta caracteristica solo mostramos el valor seleccionado y permitimos eliminarlo --}}
			@else
				@if(!empty($featuresCount[$idFeature]) && !empty($featuresCount[$idFeature][$featuresRequest[$idFeature]]))
					<input type="hidden" value="{{$featuresRequest[$idFeature]}}" name="features[{{$idFeature}}]" id="feature_{{$featuresRequest[$idFeature]}}">
					<span data-del_filter="#feature_{{$featuresRequest[$idFeature]}}" class="del_filter_js filt-act cursor"><i class="fas fa-times"></i>
						{{$featuresCount[$idFeature][$featuresRequest[$idFeature]]["value_caracteristicas_value"] }}
					</span>
				@endif
			@endif
	</div>
	@endif

@endforeach
</div>
