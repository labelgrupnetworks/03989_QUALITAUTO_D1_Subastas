@extends('errors.layout_errors')

@section('title', trans('web.head.title_app'))
@section('error_code', '404')
@section('error_message', trans("web.global.page_not_found") )
