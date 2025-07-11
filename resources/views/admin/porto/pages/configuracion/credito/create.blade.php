@extends('admin::layouts.logged')
@section('content')

<section role="main" class="content-body">

	<div class="row well header-well d-flex align-items-center">
		<div class="col-xs-9">
			<h1>{{ trans("admin-app.button.new") }} {{ trans("admin-app.title.credit") }}</h1>
		</div>
		<div class="col-xs-3">
			<a href="{{ route('credito.index') }}"
				class="btn btn-primary right">{{ trans("admin-app.button.return") }}</a>
		</div>
	</div>


	<div class="row well">

		<form action="{{ route('credito.store') }}" method="POST">
			@csrf
			@include('admin::pages.configuracion.credito._form', compact('formulario', 'fgCreditoSub'))
		</form>

	</div>

</section>

<script>
window.onload = function(){

	$('select[name="cli_creditosub"], select[name="sub_creditosub').on('change', getActualMaxRiesCli);

}

function getActualMaxRiesCli(){

	let cod_cli = $('select[name="cli_creditosub"]').val();
	let cod_sub = $('select[name="sub_creditosub"]').val();

	if(!cod_cli || !cod_sub){
		return;
	}

	$.get("{{ route('credito.subasta') }}", { cod_cli: cod_cli, cod_sub: cod_sub }, function (response) {
		$('input[name="actual_creditosub"]').val(response.actual_creditosub);
		$('input[name="nuevo_creditosub"]').attr('readonly', false).attr('min', response.actual_creditosub).attr('max', response.riesmax_cli).attr('type', 'number');
	});

	return;
}


</script>

@stop
