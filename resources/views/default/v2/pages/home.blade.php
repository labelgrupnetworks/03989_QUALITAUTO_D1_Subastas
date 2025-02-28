@extends('layouts.default')

@section('title')
	{{ trans('web.head.title_app') }}
@stop


@section('content')

@include('content.home')

@stop
