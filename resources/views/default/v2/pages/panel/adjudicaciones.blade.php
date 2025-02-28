@extends('layouts.default')

@section('content')

    <main class="container user-panel-page allotments-page">

        <div class="row">
            <div class="col-lg-3">
                @include('pages.panel.menu_micuenta')
            </div>

            <div class="col-lg-9">
				<h1>{{ trans("web.user_panel.allotments") }}</h1>

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

    </main>

    <script>
        $(document).ready(function() {
            reload_carrito();
        });
    </script>
@stop
