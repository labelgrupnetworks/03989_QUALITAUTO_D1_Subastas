@extends('layouts.default')

@section('title')
	 Validate union
@stop

@section('content')

<style>
header, footer, .header-height, .signature{
	display:none;
}
.cabeceras{
	margin-top:4rem;
	font-weight:bold;
	text-align:center;
}

.info{
	margin-top:1rem;

	text-align:center;
	overflow: hidden;
}

button{
	padding: 10px;
}
.titulo{
	text-align: center;
	font-weight: bold;
	font-size: 20px;

}
.principal{
	color:steelblue;
}

</style>


	<div class="col-xs-12 mt-2 titulo">
		Usuarios Unidos {{count($unidos)}}
	</div>
<div class="col-xs-12 mt-4 cabeceras">

		<div class="col-xs-2"> CÓDIGO</div>
		<div class="col-xs-2"> NOMBRE</div>
		<div class="col-xs-2"> NIF</div>
		<div class="col-xs-2"> TELEFONO</div>
		<div class="col-xs-2"> EMAIL</div>
		<div class="col-xs-2"> DIRECCION</div>

</div>
	@foreach($unidos as $key=> $usuario)
		@if($key> 0 && $unidos[$key-1]->id_padre_final !=$unidos[$key]->id_padre_final )
			<div class="col-xs-12 mt-1"><hr></div>
		@endif
		<div class="col-xs-12 @if($usuario->id_padre_final == $usuario->id_num ) principal  @endif">

			<div class="col-xs-2 info"> {{$usuario->numero}}</div>
			<div class="col-xs-2 info"> {{$usuario->nombre}}</div>
			<div class="col-xs-2 info"> {{$usuario->nif}}</div>
			<div class="col-xs-2 info"> {{$usuario->telefono}}</div>
			<div class="col-xs-2 info"> {{$usuario->email}}</div>
			<div class="col-xs-2 info"> {{$usuario->direccion}}, {{$usuario->poblacion}}</div>


		</div>

	@endforeach


	<div class="col-xs-12 mt-5"><hr></div>
</form>

@stop
