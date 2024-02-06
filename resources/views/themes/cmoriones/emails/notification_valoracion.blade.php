@extends('layouts.mail')

@section('content')

    <p>
        {{ $emailOptions['content']['texto'] }}
    </p>
    <p>
        <strong>{{ trans(\Config::get('app.theme') . '-app.login_register.ph_user') }}:</strong>
        <span id="Nombre">{{ $emailOptions['content']['name'] }}</span>
    </p>
    <p>
        <strong>{{ trans(\Config::get('app.theme') . '-app.login_register.email') }}:</strong>
        <span id="Email">{{ $emailOptions['content']['email'] }}</span>
    </p>
    <p>
        <strong>{{ trans(\Config::get('app.theme') . '-app.login_register.phone') }}:</strong>
        <span id="Telefono">{{ $emailOptions['content']['telf'] }}</span>
    </p>
    <p>
        <span id="Mensaje">
			<strong>Descripción:</strong>
            {!! $emailOptions['content']['camposHtmlArray']['descripcion'] ?? '' !!}
			<br>
			<strong>Entidad:</strong>
			{{ $emailOptions['content']['camposHtmlArray']['entidad'] ?? '' }}
			<br>
			<strong>Propiedades:</strong>
			{{ $emailOptions['content']['camposHtmlArray']['propiedades'] ?? '' }}
		</span>
    </p>
	<p>
		<strong>Operación:</strong>
		<span id="Operacion">
			{{ $emailOptions['content']['camposHtmlArray']['operacion'] ?? '' }}
		</span>
	</p>
	<p>
		<strong>Campaña:</strong>
		<span id="Campana">
			{{ $emailOptions['content']['camposHtmlArray']['campana'] ?? '' }}
		</span>
	</p>

@stop
