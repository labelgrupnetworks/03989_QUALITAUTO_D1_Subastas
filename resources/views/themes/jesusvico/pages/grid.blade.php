@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@php
    $titleName = match (true /* $auction->subc_sub */) {
        $auction?->tipo_sub === App\Models\V5\FgSub::TIPO_SUB_VENTA_DIRECTA => 'TIENDA',
        $auction?->subc_sub === App\Models\V5\FgSub::SUBC_SUB_HISTORICO => 'SUBASTAS ANTERIORES',
        $auction?->subc_sub === App\Models\V5\FgSub::SUBC_SUB_ACTIVO => 'SUBASTA ACTUAL',
        default => 'SUBASTA',
    };
@endphp

@section('content')
    <main class="grid">

        <div class="container grid-header">
            <div class="row">

                <div class="col-12">
                    <h1>{{ $titleName }} | <b>{{ $seo_data->h1_seo }}</b></h1>
                </div>
            </div>
        </div>

        @include('content.grid')
    </main>
@stop
