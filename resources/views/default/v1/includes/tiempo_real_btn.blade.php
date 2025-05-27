@php
    use App\Models\V5\FgSub;
@endphp

<div class="container">
    <div class="row">
        @php
            $subastas = FgSub::query()
                ->select('cod_sub', '"name"', '"id_auc_sessions"', '"start"', '"end"')
                ->simpleJoinSessionSub()
                ->where('tipo_sub', FgSub::TIPO_SUB_PRESENCIAL)
                ->where('subc_sub', FgSub::SUBC_SUB_ACTIVO)
                ->get();
            $diferencia = \Config::get('app.btnPujarHoras', 2);
        @endphp

        @foreach ($subastas as $session)
            @php
                $tiempoPrevio = strtotime("-$diferencia hours", strtotime($session->start));
            @endphp

            @if (strtotime('now') > $tiempoPrevio && strtotime('now') < strtotime($session->end))
                <div class="col-xs-12 no-padding">
                    <div>
                        <a class="home-live-btn-link "
                            href="{{ \Tools::url_real_time_auction($session->cod_sub, $session->name, $session->id_auc_sessions) }}">
                            <div class="bid-online"></div>
                            <div class="bid-online animationPulseRed"></div>
                            {{ trans("$theme-app.lot.bid_live") }} {{ $session->name }}
                        </a>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
</div>
