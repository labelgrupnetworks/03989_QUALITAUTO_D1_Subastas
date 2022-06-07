<div id="modalAddFeatureContainer">
	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
		id="modalAddFeature">
		<div class="modal-dialog {{-- modal-sm --}}" role="document">

			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title">@if(!empty($featureValue->value_caracteristicas_value)) {{ trans("admin-app.title.edit") }} @else {{ trans("admin-app.title.create") }} @endif{{ $feature->name_caracteristicas }}</h5>
				</div>

				<div class="modal-body" id="modal-create-body">

					<form name="addFeatureForm" action="{{ route('multilanguage_features') }}" method="POST">
						<input type="hidden" name="id_caracteristica" value="{{ $feature->id_caracteristicas }}">

						<p>{{ config('app.locales.'.config('app.locale')) }}</p>
						<input type="text" name="feature_input" class="form-control" value="{{ $featureValue->value_caracteristicas_value ?? '' }}" @if(!empty($featureValue->value_caracteristicas_value)) readonly @endif>

						@foreach (array_keys(config('app.locales')) as $lang)

							@continue($lang == config('app.locale'))

							<p class="mt-2">{{ config("app.locales.$lang") }}</p>
							<input type="hidden" name="feature_lang_lang[]" value="{{ $lang }}">
							<input type="text" name="feature_lang_value[]" value="{{ (empty($featureValueLangs)) ? '' : $featureValueLangs->where('lang_car_val_lang', config("app.language_complete.$lang"))->first()->value_car_val_lang ?? '' }}" class="form-control">

						@endforeach

					</form>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="modalAddFeatureAccept">{{ trans("admin-app.button.save") }}</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans("admin-app.button.close") }}</button>
				</div>

			</div>

		</div>
	</div>

	<script>
		$("#modalAddFeature").on('hidden.bs.modal', function () {
    		$("#modalAddFeatureContainer").remove();
		});

		$("#modalAddFeatureAccept").on('click', event => {

			event.preventDefault();
			const form = window.addFeatureForm;
			const idFeature = $('[name="id_caracteristica"]').val();
			const newValue = $('[name="feature_input"]').val();

			$.ajax({
				type: form.method,
				url: form.action,
				data: $(form).serialize(),

				success: function(response) {

					const keyNewValue = response.idFeatureValue;
					//si estamos en subasta
					if($("select[name=valorcol_sub]").length > 0){
						if(response.new){
							$("select[name=valorcol_sub]").append('<option value="'+ keyNewValue +'">' + newValue + '</option>');
						}
						//marca el campo como seleccionado
						$("select[name=valorcol_sub]").val(keyNewValue);

						$("#modalAddFeature").modal('hide');
					}
					//si estamos en lote
					else{
						if(response.new){
							$("#feature_select_"+ idFeature).append('<option value="'+ keyNewValue +'">' + newValue + '</option>');
						}
						//marca el campo como seleccionado
						$("#feature_select_"+ idFeature).val(keyNewValue);

						$("#modalAddFeature").modal('hide');

					}


				},
				error: function (response) {
                	error(response);
            	}
			});
		});

	</script>
</div>
