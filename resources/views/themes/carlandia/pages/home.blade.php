

@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')

@php
	// $tets = (new \App\Http\Controllers\V5\LotListController())->getLotsListAllCategories();
@endphp

@include('content.home')



@stop
