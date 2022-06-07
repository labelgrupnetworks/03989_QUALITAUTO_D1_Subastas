<div class="row">

	@foreach($formularioGeneral as $k => $item)

	@if ($k != 'SUBMIT' && $k != "id")
	<div class="col-xs-12 col-md-6" style="padding-bottom:15px;">
		<div class="row">
			<div class="col-xs-4 pt-2 text-right">
				<label>{{ ucfirst($k)}}: </label>
			</div>
			<div class="col-xs-8">
				{!! $item !!}
			</div>
		</div>
	</div>
	@elseif ($k != "SUBMIT")
	{!! $item !!}
	@endif

	@endforeach
</div>


@if (!empty($id))

<br>
<hr><br><br>

<div class="row">
	<div class="col-12">
		<table class="table table-bordered table-condensed input-table">
			<thead>
				<tr>
					<td align="center">Idioma</td>
					<td align="center">Título</td>
					<td align="center">Descripción</td>
					<td align="center">Url</td>
					<td align="center">Meta título</td>
					<td align="center">Meta descripción</td>
				</tr>
			</thead>
			<tbody>
				@foreach(\Config::get('app.locales') as $lang => $name)
				<tr>
					<td>{!! ucfirst($name) !!}</td>

					@foreach($formularioTextos['es'] as $k => $info)
					<td>{!! $formularioTextos[$lang][$k] !!}</td>
					@endforeach
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>


<br>

<?php /*TABLA ORIGINAL
<hr><br><br>

<table class="" width="100%">

	<tr>
		<th></th>
		@foreach(\Config::get('app.locales') as $lang => $name)
		<th>{!! ucfirst($name) !!}</th>
		@endforeach
	</tr>

	@foreach($formularioTextos['es'] as $k => $info)
	<tr>
		<td>{{ ucfirst($k)}}: </td>
		@foreach(\Config::get('app.locales') as $lang => $name)
		<td>{!! $formularioTextos[$lang][$k] !!}</td>
		@endforeach
	</tr>
	@endforeach

</table>
*/?>
@endif

<br>
<center>{!! $formulario['SUBMIT'] !!}</center>
