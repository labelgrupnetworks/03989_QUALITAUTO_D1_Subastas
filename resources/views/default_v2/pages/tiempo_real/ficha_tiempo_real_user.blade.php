@extends('layouts.tiempo_real')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')



    @if (\Config::get('app.exchange'))
        <script src="{{ URL::asset('js/default/divisas.js') }}"></script>
    @endif


    @php
        # Fecha hasta
        $horah = $data['subasta_info']->lote_actual->end_session;
        $hastah = substr($data['subasta_info']->lote_actual->end_session, 0, 10);
        $hastah = str_replace('-', '/', $hastah);
        $fecha_finh = $hastah . $horah;
        $ministeryLicit = config('app.ministeryLicit', false);
        $currency = null;
        $divisas = null;

        $withExchange = config('app.exchange', false);
        if ($withExchange) {
            $currency = new App\libs\Currency();
            $divisas = $currency->getAllCurrencies($data['js_item']['subasta']['currency']->name);
        }

        $auctionStatus = $data['subasta_info']->status;
        $tiempo = $data['subasta_info']->lote_actual->start_session;

        if ($auctionStatus == 'stopped' || $auctionStatus == 'reload') {
            $tiempo = $data['subasta_info']->reanudacion;
        }

    @endphp

    <script>
        var ministeryLicit = @json($ministeryLicit);
        const withExchange = '{{ $withExchange }}';
        var currency = (Boolean(withExchange)) ? @json($divisas) : null;

        //solamente contiene el estado en la primera carga, no se actualiza
        const initialAuctionStatus = '{{ $auctionStatus }}';

        $(function() {

            if (initialAuctionStatus == 'stopped' || initialAuctionStatus == 'reload') {
                $('body').addClass('tr_stop');
                $('.tiempo_real')[0].style.position = "fixed";
            } else if (initialAuctionStatus != 'in_progress') {
                $('.tiempo_real')[0].style.position = "fixed";
                $('body').addClass('tr_finished');
            } else {
                $('#clock, button.start').hide();
                $(".logo").show();
                $(".subasta h3").show();
                $('body').addClass('tr_progress');

                $('.started').removeClass('hidden');

                // si aun no esta iniciada se verá la imagen en grande
                $('.colimagen').addClass('col-lg-6');
                $('.colimagen').removeClass('col-lg-12');
            }

            $(document).ready(function() {
                $(".tiempo").data('ini', new Date().getTime());
                countdown_timer($(".tiempo"));
            });

            if (initialAuctionStatus == 'ended') {
                $('.tiempo').countdown('stop');
                $('.tiempo').html(messages.neutral.auction_end);
                $('button.start').hide();
            }
        });
    </script>

    @include('includes.tr.tiempo_real_user.header')

    <div class="ficha_tr container position-relative py-3" id="ficha">

        @include('includes.tr.tiempo_real_user.clock')

        @include('includes.tr.tiempo_real_user.product')
        @include('includes.tr.tiempo_real_user.info')
        @include('includes.tr.tiempo_real_user.auction_lots')
        @include('includes.tr.tiempo_real_user.streaming')
        @include('includes.tr.tiempo_real_user.awards_messages')

    </div>

@stop
