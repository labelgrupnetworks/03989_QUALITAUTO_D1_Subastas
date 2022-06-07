<div class="col-xs-12 mb-3 mt-3">
		<div class="row d-flex flex-wrap">

			@foreach ($form as $field => $input)

			<div class="col-xs-12 @if($field != 'webmetad_web_page') col-sm-4 @endif">
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
					data-toggle="tooltip" data-placement="right"
					data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
				{!! $input !!}
			</div>
			@endforeach

			<input type="hidden" name="content_web_page">

		</div>
</div>
