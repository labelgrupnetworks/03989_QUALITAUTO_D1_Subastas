@extends('layouts.default')

@section('title')
{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
<div class="register-success color-letter">

    <div class="container min-height">
        <div class="row">
            <div class="col-xs-12 col-sm-12 text-center">
                <br><br><br><br>
                <h3 class="titlePage">{{ trans(\Config::get('app.theme').'-app.login_register.success_register')}}</h3>
                <br><br><br><br><br><br><br>

            </div>
        </div>
    </div>

</div>

@stop
