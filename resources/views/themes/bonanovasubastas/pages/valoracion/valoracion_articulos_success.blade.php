@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    <?php

    $bread[] = ['name' => $data['title']];
    ?>
    @include('includes.breadcrumb')
    <div id="">
        <div class="container" id="return-valoracion">
            <div class="">
                <h1 class="titleSingle_corp">
                    {{ trans($theme . '-app.valoracion_gratuita.succes_peticion') }}</h1>
            </div>
        </div>
    </div>
@stop
