@php
    $emp = Config::get('app.emp');
    $urlHqPhoto = $isPayed ? "/img/load/real/$emp-$num-$lin.jpg" : '';
@endphp

<div class="panel-lot-wrapper">
    <div class="panel-lot allotment-lot">
        <div class="panel-lot_img">
            <img class="img-responsive" src="{{ Tools::url_img('lote_medium', $num, $lin) }}" alt="">
        </div>
        <div class="panel-lot_ref">
            <p>
                <span class="panel-lot_label">Lote</span>
                {{ $ref }}
            </p>
        </div>
        <div class="panel-lot_desc">
            <p>{{ strip_tags($description) }}</p>
        </div>
        <div class="panel-lot_label label-price-salida">
            <span>P. Salida</span>
        </div>
        <div class="panel-lot_price-salida">
            <p class="js-divisa" value="{{ $imp_sal }}">
                {!! $currency->getPriceSymbol(2, $imp_sal) !!}
            </p>
        </div>
        <div class="panel-lot_label label-price-actual">
            <span>
                Adjudicado
            </span>
        </div>
        <div class="panel-lot_actual-price">
            <p class="js-divisa" value="{{ $imp_award }}">
                {!! $currency->getPriceSymbol(2, $imp_award) !!}
            </p>
        </div>

        <div class="panel-lot_buttons">
            <a class="btn btn-lb btn-lb-primary" href="{{ $urlHqPhoto }}"
                @if ($isPayed) download="{{ $ref }}_{{ $title }}" @endif
                @disabled(!$isPayed)>
                FOTOGRAFIA
            </a>

			<a data-codsub="{{ $lot->cod_sub }}" data-ref="{{ $lot->ref_asigl0 }}" @disabled(!$isPayed)
                @class([
                    'btn btn-lb btn-lb-secondary',
                    'js-btn-certificate' => $isPayed,
                ])>
                {{ trans("$theme-app.user_panel.certificate") }}

            </a>
        </div>

    </div>
</div>
