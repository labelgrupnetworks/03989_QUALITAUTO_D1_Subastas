@extends('layouts.default')

@section('title')
{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')
<div class="container success_registered" style="margin-top: 3rem; margin-bottom: 200px;">
    <div class="row">
        <div class="col-xs-12 col-sm-12 text-center">
            <br><br><br><br>
            <h3 class="titlePage">{{ trans($theme.'-app.login_register.success_register')}}</h3>
            <br><br>
            @if(!empty(\Config::get('app.coregistroSubalia')) && \Config::get('app.coregistroSubalia'))
            <br>
                <p>{{ trans($theme.'-app.login_register.register_subalia') }} <a href="/{{ Config::get('app.locale') }}/login/subalia">{{ trans($theme.'-app.login_register.here') }}</a></p>
            @endif
            <br><br><br><br><br><br>
        </div>
        <br>
        <br>


    </div>
</div>

@stop
