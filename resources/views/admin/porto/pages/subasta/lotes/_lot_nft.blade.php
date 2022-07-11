<div class="col-xs-12">
	<fieldset>
		<legend>{{ trans("admin-app.title.nft_lot") }}</legend>
		<div id="publish-nft-result"></div>

		<div class="row d-flex flex-wrap">
			@foreach ($formulario->nft as $field => $input)
			<div class="col-xs-12 col-sm-6">
				<label class="mt-1" for="{{$field}}">{{ trans("admin-app.fields.$field") }}</label>
				<i class="fa fa-info-circle" style="cursor: pointer; margin-left: 3px" aria-hidden="true"
					data-toggle="tooltip" data-placement="right"
					data-original-title="{{ trans("admin-app.help_fields.$field") }}"></i>
				{!! $input !!}
			</div>
			@endforeach
		</div>

		<input type="hidden" name="publish_nft" value="0">
	</fieldset>
</div>

@if($formulario->publish_nft)
<div class="col-xs-12 text-right mt-2">
	<a id="js-nft-publish" {{-- href="{{ route('subastas.lotes.publish_nft', ['cod_sub' => $cod_sub, 'ref_asigl0' => $fgAsigl0->ref_asigl0]) }}" --}} class="btn btn-primary">Publicar</a>
</div>
@endif

