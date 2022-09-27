@extends('layouts.default')

@section('content')

    <section class="container user-panel-page allotments-page">

        <div class="row">
            <div class="col-lg-3">
                @include('pages.panel.menu_micuenta')
            </div>

            <div class="col-lg-9">
                <div class="title-with-buttons w-100 d-inline-flex flex-wrap align-items-center gap-2">
                    <h1>{{ trans("$theme-app.user_panel.allotments") }}</h1>
                    <a href="#" class="btn btn-lb-primary ms-auto">{{ trans("$theme-app.user_panel.still_paid") }}</a>
                    <a href="#" class="btn btn-lb-primary">{{ trans("$theme-app.user_panel.bills") }}</a>
                </div>

                {{-- pendientes --}}
                @if ($data['adjudicaciones']->isNotEmpty())
                    @include('pages.panel.adjudicaciones.allotments_pending')
                @endif


                {{-- Transferencia --}}
				@if (!empty($data['adjudicaciones_transfer']))
                	@include('pages.panel.adjudicaciones.allotments_transfer')
				@endif

                {{-- Pagadas --}}
				@if ($data['adjudicaciones_pag']->isNotEmpty())
					@include('pages.panel.adjudicaciones.allotments_payed')
				@endif

            </div>

        </div>

    </section>

    <script>
        $(document).ready(function() {
            reload_carrito();
        });
    </script>
@stop
