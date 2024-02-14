@extends('layouts.default')

@section('title')
	{{ trans($theme.'-app.head.title_app') }}
@stop


@section('content')
{{-- No deben mostarse subastas historicas --}}
@php
	if (!empty($data['auction']) && strtoupper($data['auction']->subc_sub) == 'H') {
		header("Location: " . \URL::to(\Routing::is_home()), true, 302);
        exit();
	}
@endphp

    <?php

        if($data['auction']->subc_sub == 'H'){
            $url = \Routing::translateSeo('venta-directa');
        }elseif($data['auction']->tipo_sub == 'W'){
            $url = \Routing::translateSeo('presenciales');
        }elseif($data['auction']->tipo_sub == 'O'){
            $url = \Routing::translateSeo('subastas-online');
        }elseif($data['auction']->tipo_sub == 'V'){
            $url = \Routing::translateSeo('venta-directa');
        }


        $bread = array();

        $bread[] = array("url" =>$url, "name" =>"".trans($theme.'-app.subastas.auctions').""  );
        $bread[] = array( "name" =>$data['auction']->des_sub );
    ?>

    @include('content.ficha_subasta')
@stop
