
  @foreach($features as $idFeature => $feature)

	@if(!empty($featuresCount[$idFeature]))
		<div class="auction__filters-categories" >
				<div class=" d-flex align-items-center justify-content-space-between" role="button" data-toggle="collapse" href="#feature_{{$idFeature}}" aria-expanded="false">
					<div class="filters_titles"> {{$feature}}</div>
					<i class="fa fa-sort-down"></i>
				</div>
				@php
					$featuresRequest = request("features");
				@endphp

		<div class="auction__filters-type-list mt-1 collapse" id="feature_{{$idFeature}}" >

			{{-- Si no han seleccionado esta caracteristica mostramos el combo --}}
			@if (empty($featuresRequest[$idFeature]))

				@foreach($featuresCount[$idFeature] as $featureValue)

				<div class="radio">
					<input type="radio" name="features[{{$idFeature}}]" id="feature_{{$idFeature}}_{{$featureValue["id_caracteristicas_value"]}}" value="{{$featureValue["id_caracteristicas_value"]}}" class="filter_lot_list_js" />
					<label for="feature_{{$idFeature}}_{{$featureValue["id_caracteristicas_value"]}}" class="radio-label">{{$featureValue["value_caracteristicas_value"] }} ({{Tools::numberformat($featureValue["total"])}})</label>
				</div>
				@endforeach

				{{-- lo mismo que con input pero con select --}}
				{{-- <select name="features[{{$idFeature}}]"  class="select_lot_list_js">
					<option value=""> </option>
					@foreach($featuresCount[$idFeature] as $featureValue)
					@php
						$selected = (!empty($featuresRequest[$idFeature])  && $featuresRequest[$idFeature] == $featureValue["id_caracteristicas_value"])? 'selected="selected"' :'';
					@endphp
						<option id="feature_{{$featureValue["id_caracteristicas_value"]}}" value="{{$featureValue["id_caracteristicas_value"]}}" {{$selected}}> {{$featureValue["value_caracteristicas_value"] }} ({{Tools::numberformat($featureValue["total"])}}) </option>
					@endforeach
				</select> --}}
			{{-- si han seleccionado esta caracteristica solo mostramos el valor seleccionado y permitimos eliminarlo --}}
			@else
			@if(!empty($featuresCount[$idFeature]) && !empty($featuresCount[$idFeature][$featuresRequest[$idFeature]]))
					<input type="hidden" value="{{$featuresRequest[$idFeature]}}" name="features[{{$idFeature}}]" id="feature_{{$featuresRequest[$idFeature]}}">
					<span data-del_filter="#feature_{{$featuresRequest[$idFeature]}}" class="del_filter_js del_filter filt-act cursor"><i class="fas fa-times"></i>
						{{$featuresCount[$idFeature][$featuresRequest[$idFeature]]["value_caracteristicas_value"] }}
					</span>
				@endif
			@endif

		</div>
				<?php
					/* Codigo anterior por si hay que recuperarlo
					<div class="auction__filters-type-list mt-1 collapse " id="feature_{{$idFeature}}"  >
						@php $featuresRequest  =request("features"); @endphp
						@foreach($featuresCount[$idFeature] as $featureValue)
								<div class="input-category d-flex align-items-center ">
									<?php
										$checked = (!empty($featuresRequest[$idFeature])  && $featuresRequest[$idFeature] == $featureValue["id_caracteristicas_value"])? 'checked="checked"' :'';
									?>
									<div class="radio">
										<input type="radio" name="features[{{$idFeature}}]" id="feature_{{$featureValue["id_caracteristicas_value"]}}" value="{{$featureValue["id_caracteristicas_value"]}}" class="filter_lot_list_js"  {{$checked}} />
										<label for="feature_{{$featureValue["id_caracteristicas_value"]}}" class="radio-label">{{$featureValue["value_caracteristicas_value"] }} ({{Tools::numberformat($featureValue["total"])}})</label>
									</div>
								</div>
						@endforeach
					</div>
					*/
				?>
		</div>
		<div class="filters-auction-divider-medium"></div>
	@endif
@endforeach
