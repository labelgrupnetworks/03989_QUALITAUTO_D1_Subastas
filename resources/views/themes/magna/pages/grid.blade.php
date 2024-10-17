@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    <main class="grid">

		<section class="grid-auction-info">
			@include('includes.grid.auction_info')
		</section>

		<section>
			@include('content.grid')
		</section>
    </main>
@stop
