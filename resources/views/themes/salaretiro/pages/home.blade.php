
@php
if(empty($data['seo'])){
	$data['seo'] = new \Stdclass();
}
$data['seo']->canonical="www.salaretiro.com/es";
@endphp
@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')

@include('content.home')

@stop
