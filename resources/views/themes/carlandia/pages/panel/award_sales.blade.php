@extends('layouts.default')

@section('title')
    {{ trans(\Config::get('app.theme') . '-app.head.title_app') }}
@stop

@section('content')

    @php
    use Illuminate\Support\Carbon;
    @endphp



    <div class="account-user panel-user">
        <div class="container">
            <div class="row">

                <div class="col-xs-12 mt-2">
                    <div class="user-sale-title">
                        <span><a class="link-sale-noactive"
                                href="{{ route('panel.active-sales', ['lang' => config('app.locale')]) }}">Ofertas
                                Vigentes</a></span>
                        <span class="link-sale-active">OFERTAS RESERVADAS</span>

                        <a target="_blank" role="button"
                            href="{{ route('panel.download-sales', ['lang' => config('app.locale'), 'active' => false]) }}"
                            class="btn button-principal">Descargar Excel</a>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="row d-flex align-items-center">

                        <div class="col-xs-12 col-md-6 mt-1">
                            @if ($selector)
                                <div class="form-sales-wrapper">
                                    <form name="form-sales" action="" class="d-flex align-items-end gap" style="gap: 5px">
                                        <label for="">Vendedor</label>
                                        {!! $selector !!}
                                    </form>
                                </div>
                            @endif
                        </div>

                        <div class="col-xs-12 @if ($selector) {{ 'col-md-6' }} @endif mt-1">
                            @if ($sales->count() > 0)
                                <span style="float: right;"><b>Total:</b> {{ $sales->count() }} Vehículos</span>
                            @endif
                        </div>

                    </div>
                </div>

                @forelse ($sales as $lot)
                    <div class="col-xs-12 wrapper-lot mt-2">

                        <div class="lot-sale p-2">

                            <div class="lot-sale-title mb-1">
                                @php
                                    $daysToSell = (new Carbon($lot->fecalta_asigl0))->diffInDays($lot->fecha_csub);
                                @endphp

                                <p class="m-0">
                                    <span><b>{{ $lot->descweb_hces1 }}</b></span>
                                    <br>
                                    <span>
                                        <b>Oferta Nº:</b> {{ $lot->ref_asigl0 }} <b>Publicada:</b>
                                        {{ Tools::getDateFormat($lot->fecalta_asigl0, 'Y-m-d H:i:s', 'd/m/y') }}
                                        <b>Vendido:</b>
                                        {{ Tools::getDateFormat($lot->fecha_csub, 'Y-m-d H:i:s', 'd/m/Y') }} <b>Vendido
                                            en:</b> {{ $daysToSell }} Dias
                                    </span>
                                </p>

                                @if ($lot->tipo_sub == 'O')
                                    <span class="label label-auction">Subasta</span>
                                @else
                                    <span class="label label-direct-sale">Venta Directa</span>
                                @endif
                            </div>

                            <div class="lot-sale-content">

                                <div class="lot-sale-image">
                                    <a
                                        href="{{ Tools::url_lot($lot->cod_sub,$lot->id_auc_sessions,$lot->name,$lot->ref_asigl0,$lot->num_hces1,$lot->webfriend_hces1,$lot->descweb_hces1) }}">
                                        <img src="{{ Tools::url_img('lote_medium', $lot->num_hces1, $lot->lin_hces1) }}"
                                            class="img-responsive">
                                    </a>

                                </div>

                                <div class="lot-sale-data">

                                    <div class="lot-sale-values row">

                                        <div class="col-xs-12 col-lg-5">

                                            <p class="m-0">Valor de mercado:
                                                <span>{{ Tools::moneyFormat($lot->pc_hces1, trans("$theme-app.lot.eur")) }}<span>
                                            </p>

											@if($lot->tipo_sub == 'O')
                                            <p class="m-0">Precio Comprar Ya:
                                                <span>{{ Tools::moneyFormat($lot->comprar, trans("$theme-app.lot.eur"), 2) }}</span>
                                            </p>
											@endif

                                            <p class="m-0">Precio Mínimo/Reserva:
                                                <span>
                                                    {{ Tools::moneyFormat($lot->reserva, trans("$theme-app.lot.eur"), 2) }}
                                                    <span>
                                            </p>

                                            <p class="m-0"><b>Precio de venta:
                                                    <span>{{ Tools::moneyFormat($lot->implic_hces1, trans("$theme-app.lot.eur"), 2) }}<span></b>
                                            </p>
                                            <p class="m-0"><b>Tipo de Venta:</b> <span>
                                                    {{ trans("$theme-app.lot.pujrep_$lot->pujrep_asigl1") }}</span></p>

                                        </div>
										<div class="col-xs-12 col-lg-1"></div>
                                        @php
                                            $poWithCy = $lot->implic_hces1 - $lot->comprar;
                                            $poWithRm = $lot->implic_hces1 - $lot->reserva;
                                            $poWithPm = $lot->implic_hces1 - $lot->pc_hces1;
                                        @endphp
                                        <div class="col-xs-12 col-lg-6">

                                            <table class="table-sales">
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td><b>%</b></td>
														<td class="table-img-header"><img src="/themes/{{$theme}}/assets/img/triangle.png" alt="difference"></td>
                                                        {{-- <td style="font-size: 50px; line-height: 1px;">&#9652;</td> --}}
                                                    </tr>
                                                    <tr>
                                                        <td class="title">Venta <b><i>vs</i></b> Mercado:</td>
                                                        <td class="{{ $poWithPm >= 0 ? 'mine' : 'other' }}">
                                                            {{ Tools::moneyFormat(($lot->implic_hces1 * 100) / $lot->pc_hces1, '%', 0) }}
                                                        </td>
                                                        <td class="{{ $poWithPm >= 0 ? 'mine' : 'other' }}">
                                                            {{ Tools::moneyFormat($poWithPm, trans("$theme-app.lot.eur"), 2) }}
                                                        </td>
                                                    </tr>

													@if($lot->tipo_sub == 'O')
                                                    <tr>
                                                        <td class="title">Venta <b><i>vs</i></b> Comprar Ya:</td>
                                                        <td class="{{ $poWithCy >= 0 ? 'mine' : 'other' }}">
                                                            {{ Tools::moneyFormat(($lot->implic_hces1 * 100) / $lot->comprar, '%', 0) }}
                                                        </td>
                                                        <td class="{{ $poWithCy >= 0 ? 'mine' : 'other' }}">
                                                            {{ Tools::moneyFormat($poWithCy, trans("$theme-app.lot.eur"), 2) }}
                                                        </td>
                                                    </tr>
													@endif

                                                    <tr>
                                                        <td class="title">Venta <b><i>vs</i></b> Mínimo/Reserva:
                                                        </td>
                                                        <td class="{{ $poWithRm >= 0 ? 'mine' : 'other' }}">
                                                            {{ Tools::moneyFormat(($lot->max_imp_asigl1 * 100) / $lot->reserva, '%', 0) }}
                                                        </td>
                                                        <td class="{{ $poWithRm >= 0 ? 'mine' : 'other' }}">
                                                            {{ Tools::moneyFormat($poWithRm, trans("$theme-app.lot.eur"), 2) }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                    <div class="row lot-sale-offers-values">
                                        <div class="col-xs-12 col-lg-5">
                                            <p class="m-0"><b># Ofertas:
                                                    <span>{{ $lot->bids ?? 0 }}</span></b></p>
                                            <p class="m-0"><b># Leads:
                                                    <span>{{ $lot->licits ?? 0 }}</span></b></p>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                @empty
                    <div class="col-xs-12 p-3">
                        <h2 class="text-center">En este momento no tiene vehículos en esta sección</h2>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.querySelector('select[name=prop]')?.addEventListener('change', (e) => {
            document.forms['form-sales'].submit();
        });
    </script>
@stop
