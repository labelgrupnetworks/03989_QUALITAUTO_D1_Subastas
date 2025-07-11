@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div id="editbanner">
		<form method="POST"  action="{{ route('event.update', $webNewBanner->id) }}">
			@method('PUT')

			<div class="right">
				<a href="{{ route('event.index', ['ubicacion' => $webNewBanner->ubicacion, 'menu' => 'contenido']) }}" class="btn btn-primary">Volver</a>
				&nbsp;&nbsp;&nbsp;
				<button type="submit" class="btn btn-success">Guardar</a>
			</div>

			<h1>Editar {{ Str::ucfirst(str_replace('-', ' ', $webNewBanner->ubicacion)) }}</h1>
			{!! $formulario['token'] !!}
			{!! $formulario['id'] !!}
			{!! $formulario['nombre'] !!}

			<div class="row mt-2">

				<div class="col-xs-12 col-md-3">
					<label>Orden:</label>
					{!! $formulario['orden'] !!}
				</div>

				<div class="col-xs-12 col-md-3 text-center">
					<label>Activo:</label>
					{!! $formulario['activo'] !!}
				</div>

			</div>

			<div class="row mt-2">
				<div class="col-xs-12 col-md-6">
					<label>TÍtulo:</label>
					{!! $formulario['descripcion'] !!}
				</div>

			</div>

		</form>

		<br>
		<hr>
		<br>
		<p>*Se puede modificar el orden arrastrando los elementos<p>
				<div class="row">
					@foreach($bloques as $k => $bloque)

					<div class="col-xs-12 col-md-{{floor(12/sizeof($bloques))}} {{$bloque}}">

						<div class="bloqueBanner">
							<a href="javascript:nuevoItemBloque('{{$webNewBanner->id}}',{{$k}})"
								class="btn btn-primary">Nuevo</a>
							<h4>{{ucfirst($bloque)}}</h4>
							<br>
							<div class="bannerItems" id="bannerItems{{$k}}"></div>


						</div>
					</div>

					@endforeach
				</div>

	</div>
</section>

<script>
	$(document).ready(function () {

		guardaItemBloque = function(e, a) {

			$("#formLenguaje").attr('action', "{{ route('event.store') }}");

			formulario = document.getElementById("formLenguaje");
			var cuadrosTexto = $("div.langs div.note-editable");

			for (let i = 0; i < cuadrosTexto.length; i++) {
				let summer = cuadrosTexto[i].parentNode.previousSibling;
				let lang = summer.id.split("summernote_")[1];
				$('.inputSummer_texto_' + lang).val(cuadrosTexto[i].innerHTML.trim());
			}

			if (check_form(formulario)) {
				$("#formLenguaje").submit();
			}
		};


	});

</script>


@stop
