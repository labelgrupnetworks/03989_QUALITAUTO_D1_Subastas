
  @foreach($features as $idFeature => $feature)

	@if(!empty($featuresCount[$idFeature]))
		<div class="auction__filters-categories" >
				<div class=" d-flex align-items-center justify-content-space-between" role="button" data-toggle="collapse" href="#feature_{{$idFeature}}" aria-expanded="true" aria-controls="feature_{{$idFeature}}" >
					<div> {{$feature}}</div>
					<i class="fa fa-sort-down"></i>
				</div>

				<div class="auction__filters-type-list mt-1 collapse " id="feature_{{$idFeature}}"  >

					<?php
						$featuresRequest  =request("features");

					?>


					@foreach($featuresCount[$idFeature] as $featureValue)

							<div class="input-category d-flex align-items-center ">
								<?php
									$checked = (!empty($featuresRequest[$idFeature])  && $featuresRequest[$idFeature] == $featureValue["id_caracteristicas_value"])? 'checked="checked"' :'';
								?>
								<div class="radio">
									<input type="radio" name="features[{{$idFeature}}]" id="feature_{{$featureValue["id_caracteristicas_value"]}}" value="{{$featureValue["id_caracteristicas_value"]}}" class="filter_lot_list"  {{$checked}} />
									<label for="feature_{{$featureValue["id_caracteristicas_value"]}}" class="radio-label">{{$featureValue["value_caracteristicas_value"] }} ({{Tools::numberformat($featureValue["total"])}})</label>
								</div>
							</div>

					@endforeach


				</div>
		</div>
		<div class="filters-auction-divider-medium"></div>
	@endif
@endforeach
