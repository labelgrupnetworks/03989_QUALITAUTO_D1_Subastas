@extends('layouts.default')

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop

@section('content')
	<script>window.location = "{{ \Tools::url_auction($data['auction_list'][0]->cod_sub,$data['auction_list'][0]->name,$data['auction_list'][0]->id_auc_sessions) }}";</script>
@stop
