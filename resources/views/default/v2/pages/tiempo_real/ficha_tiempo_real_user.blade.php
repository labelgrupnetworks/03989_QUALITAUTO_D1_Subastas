@extends('layouts.tiempo_real')

@section('title')
    {{ trans('web.head.title_app') }}
@stop

@section('content')

    @if (\Config::get('app.exchange'))
        <script src="{{ URL::asset('js/default/divisas.js') }}"></script>
    @endif

    @php
        # Fecha hasta
		$loteActual = $data['subasta_info']->lote_actual;
        $horah = $loteActual->end_session;
        $hastah = substr($loteActual->end_session, 0, 10);
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
        $tiempo = $loteActual->start_session;

        if ($auctionStatus == 'stopped' || $auctionStatus == 'reload') {
            $tiempo = $data['subasta_info']->reanudacion;
        }

		$urlLot = config('app.url') . Routing::translateSeo('lote');
		$auctionLots = \App\Models\V5\FgAsigl0::JoinFghces1Asigl0()
                ->JoinSessionAsigl0()
                ->select('num_hces1', 'lin_hces1', 'impsal_hces1', 'sub_asigl0', 'ref_asigl0', 'cerrado_asigl0', 'webfriend_hces1')
                ->where('SUB_ASIGL0', $data['subasta_info']->cod_sub)
                ->where('auc."reference"', $data['subasta_info']->reference)
                ->where('RETIRADO_ASIGL0', 'N')
                ->where('OCULTO_ASIGL0', 'N')
				->orderby("nvl(orden_hces1, ref_hces1), nvl(orden_hces1, 99999999999)")
                ->get();

		$auctionLots = $auctionLots->map(function ($item) use ($loteActual) {
			$item->url = Tools::url_lot($item->sub_asigl0, $loteActual->id_auc_sessions, '', $item->ref_asigl0, $item->num_hces1, $item->webfriend_hces1, '');
			return $item;
		});
    @endphp

    <script>
        var ministeryLicit = @json($ministeryLicit);
        const withExchange = '{{ $withExchange }}';
        var currency = (Boolean(withExchange)) ? @json($divisas) : null;
		const auctionLots = @json($auctionLots);

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
