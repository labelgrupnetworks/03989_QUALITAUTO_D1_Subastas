@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

    <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 text-center color-letter">
					@php

							$pagina = new App\Models\Page();
							$menuEstaticoHtml  = $pagina->getPagina(Str::upper(\Config::get("app.locale")),"MENUSUBASTAS");


					@endphp
					{!! $menuEstaticoHtml->content_web_page!!}
                </div>
            </div>
        </div>

    @include('content.subastas')
@stop
