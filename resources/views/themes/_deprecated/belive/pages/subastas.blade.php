@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
    <?php 
    $bread[] = array("name" => $data['name'] );
    
    ?>
    <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 color-letter">
                        <h3 class="titlePage"> {{ $data['name'] }}</h3>

                    @include('includes.breadcrumb')
                </div>
            </div>
        </div>
    
    @include('content.subastas')
@stop