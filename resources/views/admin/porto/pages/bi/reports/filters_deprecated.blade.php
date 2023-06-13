<div class="col-xs-6" style="background-color: #ffe7e7" >
	<div class="sidebar-right-wrapper">
		<center><h3>{{ trans("admin-app.reportsBi.filters") }}</h3></center>
		<form action="{{ route('bi_report',["report"=>request("report")]) }}" name="biReload" id="biReload">
			{{-- @csrf --}}
			<div class="row d-flex flex-wrap">


				@foreach ($formulario as $field => $input)
				<div class="col-xs-12 ">
					<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
					{{-- <i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
						data-toggle="tooltip" data-placement="right"
						data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i> --}}
					{!! $input !!}
				</div>
				@endforeach


				<div class="col-xs-12 text-center mt-2 mb-2">
					<button type="submit" class="btn btn-default">{{ trans("admin-app.button.search") }}</button>
				</div>

				<div class="col-xs-12 text-center mt-2 mb-2">
					<button onclick="refreshAll()" class="btn btn-default">{{ trans("admin-app.button.restart") }}</button>
				</div>

			</div>

		</form>

	</div>
</div>
