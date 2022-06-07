@extends('layouts.default')

@push('stylesheets')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css" />
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
@endpush

@push('javascripts')
<script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
@endpush

@section('title')
	{{ trans(\Config::get('app.theme').'-app.head.title_app') }}
@stop


@section('content')
{{-- No debe mostarse el grid de subastas historicas --}}
@php
	if (!empty($auction) && strtoupper($auction->subc_sub) == 'H') {
		header("Location: " . \URL::to(\Routing::is_home()), true, 302);
        exit();
	}
@endphp

<script src="{{ URL::asset('vendor/tiempo-real/node_modules/socket.io/node_modules/socket.io-client/socket.io.js') }}"></script>

{{-- Modificada condicion respecto al grid old para mostra siempre que no se este en ventadirecta --}}
@if(!empty($auction) && strtoupper($auction->tipo_sub) != 'V')
<script src="{{ Tools::urlAssetsCache('/themes/'.$theme.'/custom_node_lotlist.js') }}"></script>
<script src="{{ URL::asset('js/hmac-sha256.js') }}"></script>
@endif

<script>
	var cod_sub = '{{ $auction->cod_sub ?? 0 }}';
	routing.node_url = '{{ Config::get("app.node_url") }}';
</script>

    @include('content.grid')
@stop

