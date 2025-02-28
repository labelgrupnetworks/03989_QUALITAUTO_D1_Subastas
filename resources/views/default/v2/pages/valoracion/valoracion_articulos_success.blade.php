@extends('layouts.default')

@section('title')
    {{ trans('web.head.title_app') }}
@stop

@php
    $bread[] = ['name' => $data['title']];
@endphp

@section('content')
    <main>
        <div class="container" id="return-valoracion">
			@include('includes.breadcrumb')
			<div class="py-5">
				<h1 class="text-center">
					{{ trans("web.valoracion_gratuita.succes_peticion") }}
				</h1>
			</div>
        </div>
    </main>

    <script>
        ga('send', 'event', 'tasacion', 'confirmada');
    </script>
@stop
