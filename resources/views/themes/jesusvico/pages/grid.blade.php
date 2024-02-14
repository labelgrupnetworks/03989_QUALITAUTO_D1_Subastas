@extends('layouts.default')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@php
    $titleName = match (true /* $auction->subc_sub */) {
        $auction?->tipo_sub === App\Models\V5\FgSub::TIPO_SUB_VENTA_DIRECTA => trans("$theme-app.subastas.store"),
        $auction?->subc_sub === App\Models\V5\FgSub::SUBC_SUB_HISTORICO => trans("$theme-app.subastas.previous_auctions"),
        $auction?->subc_sub === App\Models\V5\FgSub::SUBC_SUB_ACTIVO => trans("$theme-app.subastas.current_auction"),
        default => trans("$theme-app.subastas.inf_subasta_subasta"),
    };
@endphp

@section('content')
    <main class="grid">

        <div class="container grid-header">
            <div class="row">

                <div class="col-12">
                    <h1 class="text-uppercase">{{ $titleName }} | <b>{{ $seo_data->h1_seo }}</b></h1>
                </div>
            </div>
        </div>

        @include('content.grid')
    </main>
@stop
