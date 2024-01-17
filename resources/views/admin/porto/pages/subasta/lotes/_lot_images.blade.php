<div class="col-xs-12">
	<fieldset>
		<legend>{{ trans("admin-app.title.order_images") }}</legend>

		@if (!empty($formulario->imagen))
		<div class="row">
			<div class="col-xs-12 col-md-10">
				<label for="imagen">{{ trans("admin-app.fields.images") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
					data-toggle="tooltip" data-placement="right"
					data-original-title="{{ trans("admin-app.help_fields.imagen_sub") }}"></i>
				{!! $formulario->imagen['image'] !!}
			</div>
		</div>

		@endif

		<div class="images-grid sortable mt-3" data-child="test">


			@foreach ($images as $image)
			<div class="img-wrapper" style="background-image: url({{$image . "?a=" . rand()}})"
				id="imagen{{$loop->index}}">
				<p class="images-grid-delete"><i class="fa fa-trash-o" onclick="javascript:borrarImagenLote('{{$loop->index}}', '{{ $image }}')" aria-hidden="true"></i></p>
				<input type="hidden" name="images_url[]" value="{{$image}}">
			</div>
			@endforeach

		</div>
	</fieldset>
</div>

<!-- Modal -->
<div class="modal fade" id="loadMe" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
	<div class="modal-dialog modal-sm" role="document">
	  <div class="modal-content">
		<div class="modal-body text-center">
		  <div class="custom-spinner"></div>
		  <div clas="custom-spinner-text">
			<p>{{ trans("$theme-app.information.processing_data") }} <br><br></p>
		  </div>
		</div>
	  </div>
	</div>
</div>

@push('admin-js')
	<script src="{{ $base_url }}/vendor/heic2any/heic2any.js"></script>
@endpush

<script>
	$(document).ready(function () {

		/**
		 * @api: https://api.jqueryui.com/sortable/
		 * */
		$(".sortable").sortable({

			//containment: "parent", //delimita el movimieno al div padre
			opacity: 0.5,
			placeholder: "sortable-placeholder",
			tolerance: "pointer",
			items: $(this).data('child'),
			over: function (event, ui) {
				$(ui.helper).css('border', '2px dashed red');
				$(ui.placeholder).css('border', '2px dashed #000').css('border-radius','10px');
				$(ui.item).css('border', '1px solid gray');

			}
		});

	});


</script>
