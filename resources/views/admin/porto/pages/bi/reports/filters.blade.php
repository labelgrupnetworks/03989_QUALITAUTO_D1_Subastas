<aside id="sidebar-right-bi" class="sidebar-right">
	<div class="nano">

		<div class="nano-content">
			<a href="#" class="mobile-close visible-xs">
				Collapse <i class="fa fa-chevron-right"></i>
			</a>

			<div class="sidebar-right-wrapper">
				<h6>Filtros</h6>

				<form action="{{ route('bi_report',["report"=>request("report")]) }}" name="biReload" id="biReload">
					{{-- @csrf --}}
					<div class="row d-flex flex-wrap">

						@foreach ($formulario as $field => $input)
						<div class="col-xs-12">
							<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
							{{-- <i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
								data-toggle="tooltip" data-placement="right"
								data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i> --}}
							{!! $input !!}
						</div>
						@endforeach


						<div class="col-xs-12">
							<button type="submit" class="btn btn-default mt-2">{{ trans("admin-app.button.search") }}</button>
							<button onclick="refreshAll()" class="btn btn-default mt-2">{{ trans("admin-app.button.restart") }}</button>
						</div>

					</div>

				</form>

			</div>
		</div>
	</div>
</aside>
