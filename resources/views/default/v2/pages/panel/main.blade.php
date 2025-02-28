@extends('layouts.default')

@section('title')
	{{ trans('web.head.title_app') }}
@stop


@section('content')
<?php echo($data['section']) ?>
@include('pages.panel.'. $data['section'])


@stop
