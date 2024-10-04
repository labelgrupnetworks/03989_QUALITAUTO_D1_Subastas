@include('includes.ficha._pujas_ficha_info')

<div class="ficha-puja-o">

    <div @class([
        'ficha-info-block',
        'hidden' => empty($data['js_item']['user']['ordenMaxima']),
    ])>
        <span>{{ trans("$theme-app.lot.max_puja") }}</span>

        <p>
            <span id="tuorden">
                {{ $data['js_item']['user']['ordenMaxima'] ?? 0 }}
            </span>
            {{ trans("$theme-app.subastas.euros") }}
        </p>

    </div>

    @if (
        $lote_actual->cerrado_asigl0 == 'N' &&
            $lote_actual->fac_hces1 == 'N' &&
            strtotime('now') > strtotime($lote_actual->start_session) &&
            strtotime('now') < strtotime($lote_actual->end_session))
        <div class="ficha-info-block">

            <a class="btn btn-block btn-live"
                href='{{ Routing::translateSeo('api/subasta') . $lote_actual->cod_sub . '-' . str_slug($lote_actual->name) . '-' . $lote_actual->id_auc_sessions }}'
                target="_blank">
                {{ trans("$theme-app.lot.bid_live") }}
            </a>
        </div>
    @endif

    <div class="ficha-info-block">

        @if (Session::has('user') && Session::get('user.admin'))
            <div class="ficha-puja_admin-licit form-group-lg form-inline has-error">
                <input class="form-control" id="ges_cod_licit" name="ges_cod_licit" type="text"
                    placeholder="Código de licitador">

                @if ($lote_actual->subabierta_sub == 'P')
                    <input id="tipo_puja_gestor" type="hidden" value="abiertaP">
                @endif
            </div>
        @endif

        <div class="bid_amount-wrapper">
            <input class="form-control control-number" id="bid_amount" type="text"
                value="{{ $data['precio_salida'] }}" placeholder="{{ $data['precio_salida'] }}">
        </div>
        <button data-from="modal" type="button" type="button" @class([
            'btn btn-lb-primary btn-light lot-action_pujar_on_line',
            'add_favs' => Session::has('user'),
        ])
            ref="{{ $lote_actual->ref_asigl0 }}" codsub="{{ $lote_actual->cod_sub }}">
            {{ trans("$theme-app.lot.pujar") }}
        </button>

        <div class="ficha-puja_insert-bid">
            <p>{{ trans("$theme-app.lot.insert_max_puja") }}</p>
            <p>
                @if (count($lote_actual->pujas) > 0)
                    {{ trans("$theme-app.lot.next_min_bid") }}
                @else
                    {{ trans("$theme-app.lot.min_puja") }}
                @endif

                <span class="siguiente_puja"></span>
                {{ trans("$theme-app.subastas.euros") }}
            </p>
        </div>
    </div>
</div>

<div class="modal modal-toast fade" id="postVentaModal" role="dialog" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans("$theme-app.lot.post_venta_title") }}</h4>
            </div>

            <div class="modal-body">
                <p>{{ trans("$theme-app.lot.post_venta_content") }}</p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-lg btn-primary" data-dismiss="modal"
                    type="button">{{ trans("$theme-app.head.close") }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        //showPostVentaModal(auction_info.lote_actual);

        //calculamos la fecha de cierre
        //$("#cierre_lote").html(format_date(new Date("{{ $lote_actual->close_at }}".replace(/-/g, "/"))));
        $("#actual_max_bid").bind('DOMNodeInserted', function(event) {
            if (event.type == 'DOMNodeInserted') {

                $.ajax({
                    type: "GET",
                    url: "/lot/getfechafin",
                    data: {
                        cod: cod_sub,
                        ref: ref
                    },
                    success: function(data) {

                        if (data.status == 'success') {
                            $(".timer").data('ini', new Date().getTime());
                            $(".timer").data('countdown', data.countdown);


                        }
                    }
                });
            }
        });
    });

    function showPostVentaModal({
        tipo_sub,
        compra_asigl0
    }) {

        const isMobile = window.matchMedia("(max-width: 600px)").matches;
        const keyStorage = 'postVentaModal';

        //en mobile solo se muestra una vez al dia
        if (isMobile && !shouldExecuteOncePerDay(keyStorage)) {
            return;
        }

        if (tipo_sub !== 'W' || compra_asigl0 !== 'S') {
            return;
        }

        $('#postVentaModal').modal({
            show: true,
            backdrop: false,
            keyboard: false
        });

        $('#postVentaModal').on('shown.bs.modal', function() {
            $('body').removeClass('modal-open');
        });
    }

    function shouldExecuteOncePerDay(keyStorage) {

        if (!window.localStorage) {
            return true;
        }

        const now = new Date();
        const last = new Date(localStorage.getItem(keyStorage));
        if (last.getDate() !== now.getDate()) {
            localStorage.setItem(keyStorage, now);
            return true;
        }
        return false;
    }
</script>
