@foreach($features as $idFeature => $feature)
	{{-- quito esta condicion del continue para que salga siempre la carroceria|| ($idFeature == 13 && empty($featuresRequest[54])) --}}
	@continue(!in_array($idFeature, $seeBeforeCategories) )

	@if(!empty($featuresCount[$idFeature]) || in_array($idFeature, $rangesIds))
		<div class="auction__filters-categories" >

			@php
				$featuresRequest = request("features");
			@endphp

			@if (in_array($idFeature, $radioIds) && (empty($featuresRequest[$idFeature]) || is_array($featuresRequest[$idFeature])))
				<div class="auction__filters-collapse d-flex justify-content-space-between" role="button" data-toggle="collapse" href="#auction_feature_{{$idFeature}}" aria-expanded="false" aria-controls="auction_feature">
					<div>{{$feature}}</div>
					<i class="fa fa-sort-down"></i>
				</div>
			@else
				<div class="">
					<div>{{$feature}}</div>
				</div>
			@endif

			{{-- Si no han seleccionado esta caracteristica mostramos el combo --}}
			@if (empty($featuresRequest[$idFeature]) || is_array($featuresRequest[$idFeature]) )

			@if (in_array($idFeature, $radioIds))

			<div class="auction__filters-type-list mt-1 collapse @if(in_array($idFeature, $collapseOpenIds)) in @endif" id="auction_feature_{{$idFeature}}">
				<div class="radio-wrapper">
				@foreach($featuresCount[$idFeature] as $featureValue)
				@php
					$checked = (!empty($featuresRequest[$idFeature])  && $featuresRequest[$idFeature] == $featureValue["id_caracteristicas_value"])
				@endphp

				<label class="radio-inline d-flex mb-1" style="gap: 5px">
					<input type="radio" name="features[{{$idFeature}}]" class="select_lot_list_js" id="feature_{{$featureValue["id_caracteristicas_value"]}}" value="{{$featureValue["id_caracteristicas_value"]}}">

					@if(in_array($idFeature, [35]))<img src="/themes/{{$theme}}/assets/features/{{$idFeature}}/{{ mb_strtolower($featureValue["value_caracteristicas_value"]) }}.png" alt="" style="max-width: 45px">@endif

					{{$featureValue["value_caracteristicas_value"] }} ({{Tools::numberformat($featureValue["total"])}})
				</label>

				@endforeach
				</div>
			</div>

			@elseif(in_array($idFeature, $rangesIds) && !empty($minMaxRanges[$idFeature]))
			@php
				$minSelect = $featuresRequest[$idFeature][0] ?? null;
				$maxSelect = $featuresRequest[$idFeature][1] ?? null;
			@endphp

			{!! \FormLib::SelectRange("features[$idFeature]", "feature_$idFeature", 'filter_range_js', $minMaxRanges[$idFeature]->min, $minSelect, $minMaxRanges[$idFeature]->max, $maxSelect) !!}
			@elseif(!empty($featuresCount[$idFeature]))

			<select name="features[{{$idFeature}}]"  class="select_lot_list_js">
				<option value=""> </option>
				@foreach($featuresCount[$idFeature] as $featureValue)
				<?php
					$selected = (!empty($featuresRequest[$idFeature])  && $featuresRequest[$idFeature] == $featureValue["id_caracteristicas_value"])? 'selected="selected"' :'';
				?>
					<option id="feature_{{$featureValue["id_caracteristicas_value"]}}" value="{{$featureValue["id_caracteristicas_value"]}}" {{$selected}}> {{$featureValue["value_caracteristicas_value"] }} ({{Tools::numberformat($featureValue["total"])}}) </option>
				@endforeach
			</select>

			@endif

			{{-- si han seleccionado esta caracteristica solo mostramos el valor seleccionado y permitimos eliminarlo --}}
			@else

			@if(!empty($featuresCount[$idFeature]) && !is_array($featuresRequest[$idFeature]) && !empty($featuresCount[$idFeature][$featuresRequest[$idFeature]]))
					<input type="hidden" value="{{$featuresRequest[$idFeature]}}" name="features[{{$idFeature}}]" id="feature_{{$featuresRequest[$idFeature]}}">
					<span data-del_filter="#feature_{{$featuresRequest[$idFeature]}}" class="del_filter_js del_filter filt-act cursor"><i class="fas fa-times"></i>
						{{$featuresCount[$idFeature][$featuresRequest[$idFeature]]["value_caracteristicas_value"] }}
					</span>
				@endif
			@endif
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
