@extends('layouts.tiempo_real')

@section('title')
    {{ trans($theme . '-app.head.title_app') }}
@stop

@section('content')
    <link href="{{ Tools::urlAssetsCache('/default/v2/css/tiempo_real_proyector.css') }}" rel="stylesheet" />
    <link href="{{ Tools::urlAssetsCache('/themes/' . $theme . '/css/tiempo_real/tiempo_real_proyector.css') }}"
        rel="stylesheet" />


	@include('includes.tr.tiempo_real_proyector.v2.header')

    @php
        # Fecha hasta
        $horah = $data['subasta_info']->lote_actual->end_session;
        $hastah = substr($data['subasta_info']->lote_actual->end_session, 0, 10);
        $hastah = str_replace('-', '/', $hastah);
        $fecha_finh = $hastah . $horah;

        $tiempo = null;
        $isInit = false;
        if ($data['subasta_info']->status == 'stopped' || $data['subasta_info']->status == 'reload') {
            $tiempo = $data['subasta_info']->reanudacion;
        } elseif ($data['subasta_info']->status != 'in_progress') {
            $tiempo = $data['subasta_info']->lote_actual->start_session;
        } else {
            $tiempo = $data['subasta_info']->lote_actual->start_session;
            $isInit = true;
        }

        $isEnded = $data['subasta_info']->status == 'ended';

    @endphp

    <script>
        const isInitAuction = {{ $isInit ? 'true' : 'false' }};
        const isEndedAuction = {{ $isEnded ? 'true' : 'false' }};

        $(function() {

            if (isInitAuction) {
                $('#clock, button.start').hide();
                $(".logo").show();
                $(".subasta h3").show();

                $('.started').removeClass('hidden');
            }

            $(document).ready(function() {
                $(".tiempo").data('ini', new Date().getTime());
                countdown_timer($(".tiempo"));
            });

            if (isEndedAuction) {
                $('.tiempo').countdown('stop');
                $('.tiempo').html(messages.neutral.auction_end);
                $('button.start').hide();
            }
        });
    </script>

    {{-- <div class="ficha_tiempo_real" id="ficha"> --}}
        @include('includes.tr.tiempo_real_user.clock')
        @include('includes.tr.tiempo_real_proyector.v2.content')
        @include('includes.tr.tiempo_real_proyector.v2.info')
    {{-- </div> --}}

@stop
