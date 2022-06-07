@php
	$featuresRequest = request("features");
@endphp

{{-- cargamos las secciones que dependen de este Tsec --}}
<div class="category_level__03 collapse in" style="padding-left: 2rem;" id="subsections_{{$sec["key_sec"]}}">

	@if (empty($featuresRequest[1]) || is_array($featuresRequest[1]))
		@if (!empty($featuresCount[1]))
			@foreach($featuresCount[1] as $featureValue)
				@php
					$checked = (!empty($featuresRequest[1]) && $featuresRequest[1] == $featureValue["id_caracteristicas_value"])
				@endphp

				<label class="radio-inline d-flex mb-1" style="gap: 5px">
					<input type="radio" name="features[1]" class="select_lot_list_js"
						id="feature_{{$featureValue["id_caracteristicas_value"]}}"
						value="{{$featureValue["id_caracteristicas_value"]}}">
					{{$featureValue["value_caracteristicas_value"] }} ({{Tools::numberformat($featureValue["total"])}})
				</label>

			@endforeach
		@endif
	@else

		@if(!empty($featuresCount[1]) && !is_array($featuresRequest[1]) && !empty($featuresCount[1][$featuresRequest[1]]))
			<input type="hidden" value="{{$featuresRequest[1]}}" name="features[1]" id="feature_{{$featuresRequest[1]}}">
			<span data-del_filter="#feature_{{$featuresRequest[1]}}" class="del_filter_js del_filter filt-act cursor"><i class="fas fa-times"></i>
				{{$featuresCount[1][$featuresRequest[1]]["value_caracteristicas_value"] }}
			</span>
		@endif

	@endif

</div>
