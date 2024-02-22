@extends('errors.layout_errors')

@section('title', trans($theme.'-app.head.title_app'))
@section('error_code', '404')
@section('error_message', trans("$theme-app.global.page_not_found") )
