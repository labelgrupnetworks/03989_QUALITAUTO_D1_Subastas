<div class="col-xs-12">
	<fieldset>
		<legend>{{ trans("admin-app.title.features") }}</legend>
		<div class="row mt-1" style="display: flex; flex-wrap: wrap;">
			@foreach($features as $keyFeature=>$feature)
				<div class="col-xs-6 col-md-4 mt-2 mb-2">




					@php
						$valueFeature = "";
						#si existe el id_caracteristicas_hces1 no ponemos el texto, ya que se habrá sobreescrito el correspondiente al id
						#siempre que haya id manda el id
						if (!empty($featuresHces1[$keyFeature]) &&  empty($featuresHces1[$keyFeature]->idvalue_caracteristicas_hces1)){
							$valueFeature =$featuresHces1[$keyFeature]->value_caracteristicas_hces1;
						}

					@endphp

					@if($feature->value_caracteristicas != 'S')

						<div class="col-xs-12">
							<label> {{$feature->name_caracteristicas}}</label>
							<input type="text" id="feature_input_{{$keyFeature}}"  name="feature_input[{{$keyFeature}}]" value="{{$valueFeature }}" class="form-control">
						</div>
						@if(isMultilanguage())
							@php
								$langs = config('app.locales');
								#quitamos el idioma principal
								unset($langs[config('app.locale')]);

							@endphp
							@foreach($langs as $lang => $nameLang)
							@php
							$valueFeatureLang="";
								if(!empty($featuresHces1Lang[$lang]) && !empty($featuresHces1Lang[$lang][$keyFeature])){
									$valueFeatureLang = $featuresHces1Lang[$lang][$keyFeature]->value_car_hces1_lang;
								}

							@endphp
								<div class="col-xs-12 mt-1">
									<label> {{$feature->name_caracteristicas}} {{ config("app.locales.$lang") }}</label>
									<input type="text" id="feature_input_lang_{{$keyFeature}}"  name="feature_input_lang[{{$lang}}][{{$keyFeature}}]" value="{{ $valueFeatureLang }}" class="form-control">
								</div>
							@endforeach
						@endif

					{{-- Si usan listado de valores --}}
					@else

					<div class="col-xs-12 ">
						<label> {{$feature->name_caracteristicas}}</label>
					</div>


					<div class="col-xs-12 mb-1">
						<select id="feature_select_{{$keyFeature}}"  name="feature_select[{{$keyFeature}}]" class="form-control w-100">
							<option value="">  </option>
							@if( !empty($featuresValues[$keyFeature]))


							@php
								#si se pone el autor en la subasta deberia usarse por defecto en la obra
								if(  \Config::get("app.ArtistInExibition") && $keyFeature == \Config::get("app.ArtistCode") &&  empty($featuresHces1[$keyFeature]) ){
									$fxsub = App\Models\V5\FgSub::select("valorcol_sub")->where("COD_SUB", $cod_sub)->first();

									$featuresHces1[$keyFeature] = new \stdClass();
									$featuresHces1[$keyFeature]->idvalue_caracteristicas_hces1=$fxsub->valorcol_sub;
								}

							@endphp
								@foreach($featuresValues[$keyFeature] as $keyValue => $value)
									<option value="{{$keyValue}}" @if(!empty($featuresHces1[$keyFeature]) && $featuresHces1[$keyFeature]->idvalue_caracteristicas_hces1== $keyValue) selected="selected"   @endif> {{$value}} </option>
								@endforeach
							@endif
						</select>
					</div>






						@if(isMultilanguage())
							<div class="col-xs-12 text-right">
								<a class="btn btn-success js-create-feature" data-feature="{{ $keyFeature }}"><i class="fa fa-plus" aria-hidden="true" data-feature="{{ $keyFeature }}"></i></a>
								<a class="btn btn-default js-edit-feature" data-feature="{{ $keyFeature }}"><i style="color:black" class="fa fa-language" aria-hidden="true" data-feature="{{ $keyFeature }}"></i></a>
							</div>

						@else
							<div class="col-xs-9">
								<input type="text" id="feature_input_{{$keyFeature}}"  name="feature_input[{{$keyFeature}}]" value="{{$valueFeature }}" class="form-control">
							</div>
							<div class="col-xs-3 text-right">
								<a class=" addFeatureValue_JS btn btn-success" data-feature="{{$keyFeature}}"> {{ trans("admin-app.button.add_symbol") }} </a>
							</div>
						@endif
					@endif

				</div>
			@endforeach

		</div>
	</fieldset>
</div>
