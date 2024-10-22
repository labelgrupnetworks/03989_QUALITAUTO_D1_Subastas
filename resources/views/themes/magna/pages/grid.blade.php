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

		<section>
			<div class="container">
				<div class="row">
					<div class="col-lg-3"></div>
					<div class="col-lg-9">
						<x-howbuy-block />
					</div>
				</div>
			</div>
		</section>
    </main>
@stop
