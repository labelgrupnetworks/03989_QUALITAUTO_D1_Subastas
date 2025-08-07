@php
	use App\Support\Localization;
@endphp
<div class="row well">
    <div class="col-xs-12 mb-1 pt-1 pb-1" style="background-color: #ffe7e7">

        <div class="d-flex align-items-center" style="gap:1rem;">

            <button class="btn btn-sm btn-primary" onclick="createFeature()">
                Crear Característica
            </button>
        </div>
    </div>

    <div class="col-xs-12 table-responsive">
        <table class="table table-striped table-condensed table-responsive" style="width:100%">
            <thead>
                <th>{{ trans('admin-app.fields.id_caracteristicas') }}</th>
                <th>{{ trans('admin-app.fields.name_caracteristicas') }}</th>

				@foreach (Localization::getAvailableLocales() as $locale)
					<th>{{ trans('admin-app.fields.feature_name_' . $locale) }}</th>
				@endforeach

                <th>{{ trans('admin-app.fields.orden_caracteristicas') }}</th>
                {{-- <th>{{ trans('admin-app.fields.filtro_caracteristicas') }}</th> --}}
                <th>{{ trans('admin-app.fields.value_caracteristicas') }}</th>
                <th class="text-center">{{ trans('admin-app.fields.actions') }}</th>
            </thead>

            <tbody>
                @foreach ($features as $feature)
                    <tr>
                        <td>{{ $feature->id_caracteristicas }}</td>
                        <td>{{ $feature->name_caracteristicas }}</td>
						@foreach (Localization::getAvailableLocales() as $locale)
							<td>{{ $feature->allLanguages->where('lang_caracteristicas_lang', Localization::getLanguageComplete($locale))->first()->name_caracteristicas_lang ?? '' }}</td>
						@endforeach
                        <td>{{ $feature->orden_caracteristicas }}</td>
                        {{-- <td>{{ $feature->filtro_caracteristicas }}</td> --}}

						<td>
							@if($feature->value_caracteristicas == 'S')
								<button class="btn btn-sm btn-info"
									data-route="{{ route('admin.features.values.index', $feature->id_caracteristicas) }}"
									onclick="showFeatureValues(this)">
									<i class="fa fa-eye"></i> Ver Valores
								</button>
							@endif
						</td>

                        <td class="text-center">
                            <button class="btn btn-sm btn-primary"
                                onclick="editFeature({{ $feature->id_caracteristicas }})">
                                <i class="fa fa-edit"></i> Editar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>

</div>

{{-- modal --}}
<div class="modal fade" id="modalCreateFeature" role="dialog" aria-labelledby="modalCreateFeatureLabel"
    aria-hidden="true" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>



<script>
    function editFeature(id) {
        $('#modalCreateFeature .modal-title').text('Editar Característica');
        $('#modalCreateFeature').modal('show');
        $.ajax({
            url: '{{ route('admin.features.edit', '') }}/' + id,
            type: 'GET',
            success: function(data) {
                $('#modalCreateFeature .modal-body').html(data);
            }
        });
    }

    function createFeature() {
        $('#modalCreateFeature .modal-title').text('Crear Característica');
        $('#modalCreateFeature').modal('show');
        $.ajax({
            url: '{{ route('admin.features.create') }}',
            type: 'GET',
            success: function(data) {
                $('#modalCreateFeature .modal-body').html(data);
            }
        });
    }

	function showFeatureValues(button) {
		$('#modalCreateFeature .modal-title').text('Valores de Característica');
        $('#modalCreateFeature').modal('show');
		$.ajax({
			url: $(button).data('route'),
			type: 'GET',
			success: function(data) {
				 $('#modalCreateFeature .modal-body').html(data);
			}
		});
	}

    //on load document
    $(document).ready(function() {
        $('#modalCreateFeature').on('hidden.bs.modal', function() {
            $('#modalCreateFeature .modal-body').html('');
        });
    });
</script>
