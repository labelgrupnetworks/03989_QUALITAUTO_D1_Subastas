@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@php
    $bread[0]['url'] = url()->current();
@endphp

@section('content')
    <main class="grid">

        <div class="container grid-header">
            <div class="row">

				<div class="col-12 py-3">
					<div class="grid-auction-title">
						<h2 class="">
							{!! trans("$theme-app.lot_list.grid_seo_subtitle") !!}
						</h2>
					</div>
                </div>

                <div class="col-12">
                    @include('includes.breadcrumb')
                </div>

                <div class="col-12">
                    <h1>{{ $seo_data->h1_seo }}</h1>
                </div>
            </div>
        </div>
        @include('content.grid')
    </main>

	<section class="container-sm google-reviews-section">
		@include('includes.google_cards')
	</section>

    @if (!empty($codSub) && $codSub == 'REPRE')
        @include('includes.auction_modal')
    @endif
@stop
