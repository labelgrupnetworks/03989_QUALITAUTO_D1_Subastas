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

</style>

<form action="/AnsorenaDecisionUnion"  >
	<div class="col-xs-12 mt-2 titulo">
		Union por {{$type}} - Pendientes: {{$pendientes}}
	</div>
<div class="col-xs-12 mt-4 cabeceras">
		<div class="col-xs-1"> </div>
		{{--
		<div class="col-xs-1"> PRINCIPAL</div>
		<div class="col-xs-1"> UNIR</div>
		--}}
		<div class="col-xs-1"> CÓDIGO</div>
		<div class="col-xs-2"> NOMBRE</div>
		<div class="col-xs-1"> NIF</div>
		<div class="col-xs-1"> TELEFONO</div>
		<div class="col-xs-2"> EMAIL</div>
		<div class="col-xs-2"> DIRECCION</div>

</div>
	@foreach($usuarios as $usuario)
		<div class="col-xs-12 ">
			<div class="col-xs-1 info"> </div>
			{{--
			<div class="col-xs-1 info"><input type="radio" name="principal" value="{{$usuario->id_num}}" @if($usuario->id_num == $idPadreOld) checked="checked" @endif  >  </div>
			<div class="col-xs-1 info"> <input type="checkbox" name="clients[]" value="{{$usuario->id_num}}" checked="checked">  </div>
			--}}
			<div class="col-xs-1 info"> {{$usuario->numero}}</div>
			<div class="col-xs-2 info"> {{$usuario->nombre}}</div>
			<div class="col-xs-1 info"> {{$usuario->nif}}</div>
			<div class="col-xs-1 info"> {{$usuario->telefono}}</div>
			<div class="col-xs-2 info"> {{$usuario->email}}</div>
			<div class="col-xs-2 info"> {{$usuario->direccion}}, {{$usuario->poblacion}}</div>


		</div>

	@endforeach
	<div class="col-xs-12 mt-4 text-center" style="color:red">
		Una vez haya revisado los clientes en el ERP puede pasar al siguiente grupo, un grupo ya revisado no volverá a aparecer

	</div>
	<div class="col-xs-12 mt-2 text-center">

			<input type="hidden" name="idPadreOld" value="{{$idPadreOld}}">
			<input type="hidden" name="type" value="{{$type}}">
	 <button type="submit" name="unir" value="NO" >Siguiente</button>

	</div>
</form>

@stop
