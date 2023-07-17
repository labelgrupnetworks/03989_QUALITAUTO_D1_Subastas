@extends('layouts.default')

@section('title')
    {{ $data['data']->name_web_page }}
@stop

@section('content')


@if ($data['data']->manageable_web_page == 2)
        @include('pages.page-v2')
    @else
        @include('pages.page-v1')
    @endif
@stop
