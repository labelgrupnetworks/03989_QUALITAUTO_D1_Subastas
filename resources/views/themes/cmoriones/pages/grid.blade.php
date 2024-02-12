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

				<div class="col-12 py-2">
					<div class="grid-auction-title">
						<h2 class="">
							La Web DE LOS EXPERTOS EN <br>Subastas Inmobiliarias y  NPLS  en españa. <br> Y TODO LO QUE NECESITAS PARA CONVERTIRTE <br>EN UN INVERSOR inmobiliario
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

    @if (!empty($codSub) && $codSub == 'REPRE')
        @include('includes.auction_modal')
    @endif
@stop
