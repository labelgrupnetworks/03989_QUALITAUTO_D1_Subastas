@php
$all_adj_pag = [];
$sub = new \App\Models\Subasta();

foreach ($data['adjudicaciones_pag'] as $temp_adj) {
    $all_adj_pag[$temp_adj->cod_sub]['lotes'][] = $temp_adj;
}
foreach ($all_adj_pag as $key_inf => $value) {
    $sub->cod = $key_inf;
    $all_adj_pag[$key_inf]['inf'] = $sub->getInfSubasta();
}
@endphp

<div class="accordion my-3">
    <h2 class="accordion-item accordion-header" id="accordion-payed-heading">
        <button class="accordion-button" type="button" data-bs-toggle="collapse"
            data-bs-target="#allotemnts-payed-collapse" aria-expanded="true" aria-controls="allotemnts-payed-collapse">
            {{ trans("$theme-app.user_panel.bills") }}
        </button>
    </h2>

    <div id="allotemnts-payed-collapse" class="accordion-collapse collapse show"
        aria-labelledby="#accordion-payed-heading">
        <div class="accordion-body p-0">

            {{-- subastas --}}
            <div class="accordion">
                @foreach ($all_adj_pag as $key_sub => $all_inf)

                    <h2 class="accordion-item accordion-header" id="{{ $all_inf['inf']->cod_sub }}-heading_pag">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#{{ $all_inf['inf']->cod_sub }}_pag" aria-expanded="true"
                            aria-controls="{{ $all_inf['inf']->cod_sub }}_pag">
                            {{ $all_inf['inf']->name }}
                        </button>
                    </h2>

                    <div id="{{ $all_inf['inf']->cod_sub }}_pag" class="accordion-collapse collapse show"
                        aria-labelledby="#{{ $all_inf['inf']->cod_sub }}-heading_pag">

                        <div class="table-to-columns">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <th>{{ trans("$theme-app.user_panel.lot") }}</th>
                                        <th style="max-width: 300px">{{ trans("$theme-app.user_panel.description") }}</th>
										<th>{{ trans("$theme-app.lot.lot-price") }}</th>
                                        <th>{{ trans("$theme-app.user_panel.price") }}</th>
                                        <th>{{ trans("$theme-app.user_panel.price_comision") }}</th>
                                        <th>{{ trans("$theme-app.user_panel.price_clean") }}</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($all_inf['lotes'] as $inf_lot)
                                        @php
                                            $url_friendly = str_slug($inf_lot->titulo_hces1);
                                            $url_friendly = Routing::translateSeo('lote') . $inf_lot->cod_sub . '-' . str_slug($inf_lot->name) . '-' . $inf_lot->id_auc_sessions . '/' . $inf_lot->ref_asigl0 . '-' . $inf_lot->num_hces1 . '-' . $url_friendly;
                                            $precio_remate = Tools::moneyFormat($inf_lot->himp_csub, trans("$theme-app.lot.eur"));
											$comision = Tools::moneyFormat($inf_lot->base_csub + $inf_lot->base_csub_iva, trans("$theme-app.lot.eur"), 2);

											$total_price = $inf_lot->himp_csub + $inf_lot->base_csub + $inf_lot->base_csub_iva;
											$precio_limpio_calculo = Tools::moneyFormat($total_price, trans("$theme-app.lot.eur"), 2);
                                        @endphp

                                        <tr>
                                            <td class="td-img">
                                                <a href="{{ $url_friendly }}">
                                                    <img src="{{ \Tools::url_img('lote_small', $inf_lot->num_hces1, $inf_lot->lin_hces1) }}"
                                                        class="img-fluid">
                                                </a>
                                            </td>
                                            <td data-title="{{ trans("$theme-app.user_panel.lot") }}">
                                                {{ $inf_lot->ref_asigl0 }}
                                            </td>
                                            <td data-title="{{ trans("$theme-app.user_panel.description") }}" class="td-title">
                                                <span class="max-line-2">{!! $inf_lot->descweb_hces1 !!}</span>
                                            </td>
                                            <td data-title="{{ trans("$theme-app.user_panel.starting_price") }}">
                                                {{ $inf_lot->impsalhces_asigl0 ?? 0 }}
                                                {{ trans($theme . '-app.subastas.euros') }}
                                            </td>
                                            <td data-title="{{ trans("$theme-app.user_panel.price") }}">
                                                {{ $precio_remate }}
                                            </td>
                                            <td data-title="{{ trans("$theme-app.user_panel.price_comision") }}">
                                                {{ $comision }}
                                            </td>
                                            <td data-title="{{ trans("$theme-app.user_panel.price_clean") }}">
                                                {{ $precio_limpio_calculo }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-sm d-flex align-items-center p-2 rounded-circle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <svg class="bi" width="16" height="16"
                                                            fill="currentColor">
                                                            <use
                                                                xlink:href="/bootstrap-icons.svg#three-dots-vertical" />
                                                        </svg>
                                                    </button>

                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="{{ $url_friendly }}"
                                                                target="_blank">{{ trans("$theme-app.user_panel.see_lot") }}</a></li>
                                                    </ul>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- <input class="hide" type="hidden" name="carrito[{{$inf_lot->sub_csub}}][{{$inf_lot->ref_csub}}][envios]" value='1'> --}}

                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

</div>
