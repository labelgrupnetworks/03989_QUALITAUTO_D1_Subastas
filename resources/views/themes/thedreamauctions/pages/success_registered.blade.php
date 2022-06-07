@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<div class="container" style="height: 50vh;">
    <div class="row h-100">
        <div class="col-xs-12 h-100 d-flex flex-column justify-content-space-between align-items-center justify-content-space-around">
            <h3 class="titlePage">{{ trans(\Config::get('app.theme').'-app.login_register.success_register')}}</h3>
            @if(!empty(\Config::get('app.coregistroSubalia')) && \Config::get('app.coregistroSubalia'))
                <p>{{ trans(\Config::get('app.theme').'-app.login_register.register_subalia') }} <a href="/{{ Config::get('app.locale') }}/login/subalia">{{ trans(\Config::get('app.theme').'-app.login_register.here') }}</a></p>
            @endif

			<div class="d-flex align-items-center justify-content-center">
				<a href="https://www.thedreamauctions.com/"
					class="btn button-principal newsletter-button">{{ trans("$theme-app.global.go_home") }}</a>
			</div>
        </div>
    </div>
</div>

@stop
