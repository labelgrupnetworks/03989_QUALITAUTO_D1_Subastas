@php
    $emp = Config::get('app.emp');
    $urlHqPhoto = $isPayed ? "/img/load/real/$emp-$num-$lin.jpg" : '';
@endphp

<div class="panel-lot-wrapper">
    <div class="panel-lot allotment-lot">
        <div class="panel-lot_img">
            <img class="img-responsive" src="{{ Tools::url_img('lote_medium', $num, $lin) }}" alt="" loading="lazy">
        </div>
        <div class="panel-lot_ref">
            <p>
                <span class="panel-lot_label">{{ trans("$theme-app.user_panel.lot") }}</span>
                {{ $ref }}
            </p>
        </div>
        <div class="panel-lot_desc">
            <p>{{ strip_tags($description) }}</p>
        </div>
        <div class="panel-lot_label label-price-salida">
            <span>{{ trans("$theme-app.user_panel.starting_price_min") }}</span>
        </div>
        <div class="panel-lot_price-salida">
            <p class="js-divisa" value="{{ $imp_sal }}" data-small-format="0,0"></p>
        </div>
        <div class="panel-lot_label label-price-actual">
            <span>
                {{ trans("$theme-app.user_panel.awarded") }}
            </span>
        </div>
        <div class="panel-lot_actual-price">
            <p class="js-divisa" value="{{ $imp_award }}" data-small-format="0,0"></p>
        </div>

        <div class="panel-lot_buttons">
            <a class="btn btn-lb btn-lb-primary" href="{{ $urlHqPhoto }}"
                @if ($isPayed) download="{{ $ref }}_{{ $title }}" @endif
                @disabled(!$isPayed)>
                {{ trans("$theme-app.lot.photo") }}
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
