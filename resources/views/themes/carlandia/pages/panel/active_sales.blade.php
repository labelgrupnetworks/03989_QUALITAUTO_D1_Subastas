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
                        <span class="link-sale-active">Ofertas Vigentes</span>
                        <span>
							<a class="link-sale-noactive" href="{{ route('panel.award-sales', ['lang' => config('app.locale')]) }}">
								OFERTAS RESERVADAS
							</a>
						</span>

                        <a target="_blank" role="button"
                            href="{{ route('panel.download-sales', ['lang' => config('app.locale'), 'active' => true]) }}"
                            class="btn button-principal">Descargar Excel</a>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="row {{-- d-flex --}} align-items-center">

						<div class="col-xs-12 {{-- @if ($selector) {{ 'col-md-6' }} @endif --}} mt-1">
                            @if ($sales->count() > 0)
                                <span style="float: right;" id="contador-vehiculos"><b>Total:</b> {{ $sales->count() }} Vehículos</span>
                            @endif
                        </div>

                        <div class="col-xs-12 col-md-6 mt-1">
                            @if ($selector)
                                <div class="form-sales-wrapper">
                                    <form name="form-sales" action="" class="{{-- d-flex --}} align-items-end gap" style="gap: 5px">
                                        <label for="">Vendedor</label>
                                        {!! $selector !!}
                                    </form>
                                </div>
                            @endif
                        </div>

						<div class="col-xs-12 col-md-6 mt-1">
							<div class="form-sales-wrapper">
								<form name="form-filter" action="" class="{{-- d-flex --}} align-items-end gap" style="gap: 5px">
									<label for="select-filter">Filtros</label>
									<select id="select-filter" onblur='comprueba_campo(this)' class='form-control' name="select-filter">
										<option value="todos">Todas</option>
										<option value="ofertas">Las que tienen ofertas</option>
										<option value="minimoreserva">Las que tienen ofertas 10% inferior a precio mínimo/reserva</option>
									</select>
								</form>
							</div>
                        </div>

                    </div>
                </div>

                @forelse ($sales as $lot)
                    <div class="col-xs-12 wrapper-lot mt-2" id="{{ $lot->sub_asigl0 }}-{{ $lot->ref_asigl0 }}">

                        <div class="lot-sale p-2">

                            <div class="lot-sale-title mb-1">
                                <p class="m-0">
									@php
										//Guardar en una variable la fecha $lot->ffin_asigl0 formateada en 'dd/mm/aa (x días)'
										$date = Carbon::parse($lot->fecalta_asigl0);
										$diff = $date->diffInDays();
									@endphp
                                    <span><b>{{ $lot->descweb_hces1 }}</b></span>
                                    <br>
                                    <span>
                                        <b>Oferta Nº:</b> {{ $lot->ref_asigl0 }}
										<b>Publicada:</b>
                                        {{ Tools::getDateFormat($lot->fecalta_asigl0, 'Y-m-d H:i:s', 'd/m/y') }} ({{ $diff }} días)
                                        @if ($lot->tipo_sub == 'O')
                                            <b>Finaliza:</b>
                                            {{ Carbon::createFromFormat('Y-m-d H:i:s', $lot->ffin_asigl0)->locale('es')->diffForHumans() }}</b>
                                        @endif
                                    </span>
                                </p>

                                @if ($lot->tipo_sub == 'O')
                                    <span class="label label-auction text-center">Subasta</span>
                                @else
                                    <span class="label label-direct-sale text-center">Venta Directa</span>
                                @endif
                            </div>

                            <div class="lot-sale-content">

                                <div class="lot-sale-image">
                                    <a
                                        href="{{ Tools::url_lot($lot->cod_sub,$lot->id_auc_sessions,$lot->name,$lot->ref_asigl0,$lot->num_hces1,$lot->webfriend_hces1,$lot->descweb_hces1) }}">
                                        <img src="{{ Tools::url_img('lote_medium', $lot->num_hces1, $lot->lin_hces1) }}"
                                            class="img-responsive">
                                    </a>

                                    <a class="btn button-principal hidden-xs hidden-sm mt-1"
                                        href="{{ Tools::url_lot($lot->cod_sub,$lot->id_auc_sessions,$lot->name,$lot->ref_asigl0,$lot->num_hces1,$lot->webfriend_hces1,$lot->descweb_hces1) }}">
                                        VER OFERTA
                                    </a>
                                </div>

                                <div class="lot-sale-data">

                                    <div class="lot-sale-values row">

                                        <div class="col-xs-12 col-lg-4">

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

                                            <p class="m-0"><b>Precio Ofertado:
                                                    <span>{{ Tools::moneyFormat($lot->max_imp_asigl1 ?? 0, trans("$theme-app.lot.eur"), 2) }}<span></b>
                                            </p>

                                        </div>

										<div class="col-xs-12 col-lg-1"></div>

                                        @php
                                            $poWithCy = $lot->max_imp_asigl1 - $lot->comprar;
                                            $poWithRm = $lot->max_imp_asigl1 - $lot->reserva;
                                            $poWithPm = $lot->max_imp_asigl1 - $lot->pc_hces1;
                                        @endphp

                                        <div class="col-xs-12 col-lg-6">

											@if ($lot->bids ?? 0)
												<table class="table-sales">
													<tbody>
														<tr>
															<td></td>
															<td><b>%</b></td>
															<td class="table-img-header"><img src="/themes/{{$theme}}/assets/img/triangle.png" alt="difference"></td>
															{{-- <td style="font-size: 50px; line-height: 1px;">&#9652;</td> --}}
														</tr>
														<tr>
															<td class="title">Ofertado <b><i>vs</i></b> Mercado:</td>
															<td class="{{ $poWithPm >= 0 ? 'mine' : 'other' }}">
																{{ Tools::moneyFormat(($lot->max_imp_asigl1 * 100) / $lot->pc_hces1, '%', 0) }}
															</td>
															<td class="{{ $poWithPm >= 0 ? 'mine' : 'other' }}">
																{{ Tools::moneyFormat($poWithPm, trans("$theme-app.lot.eur"), 2) }}
															</td>
														</tr>
														@if($lot->tipo_sub == 'O')
														<tr>
															<td class="title">Ofertado <b><i>vs</i></b> Comprar Ya:
															</td>
															<td class="{{ $poWithCy >= 0 ? 'mine' : 'other' }}">
																{{ Tools::moneyFormat(($lot->max_imp_asigl1 * 100) / $lot->comprar, '%', 0) }}
															</td>
															<td class="{{ $poWithCy >= 0 ? 'mine' : 'other' }}">
																{{ Tools::moneyFormat($poWithCy, trans("$theme-app.lot.eur"), 2) }}
															</td>
														</tr>
														@endif

														<tr>
															<td class="title">Ofertado <b><i>vs</i></b> Mínimo/Reserva:
															</td>
															<td class="{{ $poWithRm >= 0 ? 'mine' : 'other' }} oferta-reserva">
																{{ Tools::moneyFormat(($lot->max_imp_asigl1 * 100) / $lot->reserva, '%', 0) }}
															</td>
															<td class="{{ $poWithRm >= 0 ? 'mine' : 'other' }}">
																{{ Tools::moneyFormat($poWithRm, trans("$theme-app.lot.eur"), 2) }}
															</td>
														</tr>
													</tbody>
												</table>
											@endif

                                        </div>


                                    </div>

                                    <div class="row lot-sale-offers-values">
                                        <div class="col-xs-12 col-lg-4">
                                            <p class="m-0"><b># Ofertas: <span class="oferta-value">{{ $lot->bids ?? 0 }}</span></b></p>
											<p class="m-0"><b># Ofertantes (Leads): <span>{{ $lot->licits ?? 0 }}</span></b></p>
                                        </div>
                                        <div class="col-xs-12 pr-0 mt-2 text-center hidden-md hidden-lg">
                                            <a class="btn button-principal"
                                                href="{{ Tools::url_lot($lot->cod_sub,$lot->id_auc_sessions,$lot->name,$lot->ref_asigl0,$lot->num_hces1,$lot->webfriend_hces1,$lot->descweb_hces1) }}">
                                                VER OFERTA
                                            </a>
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

		const sales = @json($sales);

		document.querySelector('select[name=select-filter]')?.addEventListener('change', (e) => {
			let cuenta_vehiculos = 0;
			let selected_value = e.target.value;

			for (let i in sales) {
				document.getElementById(sales[i].sub_asigl0+'-'+sales[i].ref_asigl0).classList.add('hidden');
			}

			if (selected_value == 'todos') {
				for (let i in sales) {
					cuenta_vehiculos++;
					document.getElementById(sales[i].sub_asigl0+'-'+sales[i].ref_asigl0).classList.remove('hidden');
				}
			} else if (selected_value == 'ofertas') {
				for (let i in sales) {
					if (sales[i].bids > 0) {
						cuenta_vehiculos++;
						document.getElementById(sales[i].sub_asigl0+'-'+sales[i].ref_asigl0).classList.remove('hidden');
					}
				}
			} else if (selected_value == 'minimoreserva') {
				for (let i in sales) {
					if (((sales[i].max_imp_asigl1 * 100) / sales[i].reserva) > 90) {
						cuenta_vehiculos++;
						document.getElementById(sales[i].sub_asigl0+'-'+sales[i].ref_asigl0).classList.remove('hidden');
					}
				}
			}

			let texto_total_vehiculos = '<b>Total:</b> ' + cuenta_vehiculos + ((cuenta_vehiculos == 1) ? ' Vehículo' : ' Vehículos');
			document.getElementById('contador-vehiculos').innerHTML = texto_total_vehiculos;

		});



    </script>
@stop
