@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content') 
    <?php
   
        $bread = array();        
        $bread[] = array("url" =>$data["url_bread"], "name" =>$data["name_bread"] );
        $bread[] = array( "name" =>$data['auction']->des_sub );
    ?>
    <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 text-center color-letter">
                        <h1 class="titleSingle">{{ $data["auction"]->des_sub}}</h1>

                    @include('includes.breadcrumb')
                </div>
            </div>
        </div>

    
    @include('content.ficha_subasta')
@stop