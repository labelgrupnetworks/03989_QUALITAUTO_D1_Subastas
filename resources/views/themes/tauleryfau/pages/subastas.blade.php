@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')
    <?php
    $bread[] = array("name" => $data['name'] );
    ?>


    <?php //@include('includes.breadcrumb')<?>

    @include('content.subastas')
@stop
