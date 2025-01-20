<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            @if (request('finished') == 'true')
                <h1 class="titlePage"> {{ trans(\Config::get('app.theme') . '-app.subastas.price_made_long') }}</h1>
            @else
                <h1 class="titlePage"> {{ $data['name'] }}</h1>
            @endif
        </div>
    </div>
    @php
        $seeFinish = request('finished') == 'true' ? true : false;
        if (!empty($_GET['finished'])) {
            foreach ($data['auction_list'] as $key => $sub_finished) {
                if (strtotime($sub_finished->session_end) <= time() && !$seeFinish) {
                    unset($data['auction_list'][$key]);
                } elseif (strtotime($sub_finished->session_end) > time() && $seeFinish) {
                    unset($data['auction_list'][$key]);
                }
            }
        }

        if ($seeFinish) {
            usort($data['auction_list'], function ($a, $b) {
                $compareDate = strcmp(substr($b->session_start, 0, 10), substr($a->session_start, 0, 10));
                $compareAuction = strcmp($a->cod_sub, $b->cod_sub);
                $compareReference = strcmp($a->reference, $b->reference);

                //ordenar de más reciente a más antiguo pero si son de la misma subasta ordenar por referencia
                if ($compareAuction == 0) {
                    //sumar compareReference a compareDate como decimales para que se ordene por referencia
                    //sin afectar a la comparacion de fecha
                    $compareDate += $compareReference;
                }

                return $compareDate;
            });
        }
    @endphp

    <div class="row d-flex flex-wrap">
        @if ($data['subc_sub'] != 'H')
            @foreach ($data['auction_list'] as $subasta)
                <?php
                $url_lotes = \Routing::translateSeo('subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
                $url_tiempo_real = \Routing::translateSeo('api/subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
                $url_subasta = \Routing::translateSeo('info-subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name);

                $url_lotes_no_vendidos = \Routing::translateSeo('subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions . '?no_award=1';
                ?>

                <div class="col-xs-12 col-sm-4 col-lg-3">
                    <div class="item_subasta">

                        <div class="date-sub-content">
                            @if ($subasta->session_start)
                                <?php

                                if ($subasta->tipo_sub == 'V') {
                                    //ponemso el locale time para que salga en castellano
                                    setlocale(LC_TIME, 'es_ES');

                                    $fecha = ucfirst(strftime('%B %Y', strtotime($subasta->session_start)));
                                } else {
                                    $fecha = strftime('%d/%m/%Y', strtotime($subasta->session_start));
                                }

                                ?>
                                <div class="date-sub">{{ $fecha }}</div>
                            @endif
                        </div>

                        <a href="<?= $url_lotes ?>" title="{{ $subasta->name }}">
                            <div class="img-lot">
                                <img class="img-responsive"
                                    src="{{ Tools::url_img_session('subasta_medium', $subasta->cod_sub, $subasta->reference) }}"
                                    alt="{{ $subasta->name }}" />
                            </div>
                        </a>
                        <div class="item_subasta_item text-center">
                            {{ $subasta->name }}
                        </div>

                        <p>
                            <a class="btn btn-subasta" href="{{ $url_subasta }}" title="{{ $subasta->name }}">
                                @if ($subasta->tipo_sub == 'V')
                                    {{ trans(\Config::get('app.theme') . '-app.subastas.see_venta_directa') }}
                                @else
                                    {{ trans(\Config::get('app.theme') . '-app.subastas.see_subasta') }}
                                @endif
                            </a>
                        </p>

                        <p><a class=" btn btn-lotes btn-color" href="{{ $url_lotes }}"
                                title="{{ $subasta->name }}">{{ trans(\Config::get('app.theme') . '-app.subastas.see_lotes') }}</a>
                        </p>

                        <p>
                            @if (!empty(request('finished') && filter_var(request('finished'), FILTER_VALIDATE_BOOLEAN)))
                                <a class=" btn btn-lotes btn-color" href="{{ $url_lotes_no_vendidos }}"
                                    title="{{ $subasta->name }}">{{ trans(\Config::get('app.theme') . '-app.subastas.lotes_no_vendido') }}</a>
                            @endif
                        </p>

                        @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time() && $subasta->subastatr_sub == 'S')
                            <p class="text-center" style="">
                                <a class="btn btn-block btn-live" href="{{ $url_tiempo_real }}" style=""
                                    target="_blank">{{ trans("$theme-app.subastas.bid_live") }}</a>
                            </p>
                        @endif


                        @if ($subasta->uppreciorealizado == 'S')
                            <p class="text-center">
                                <a class="btn btn-subasta"
                                    href="{{ \Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'pre') }}"
                                    title="{{ trans(\Config::get('app.theme') . '-app.grid.pdf_adj') }}"
                                    target="_blank">{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_adj') }}</a>
                            </p>
                        @endif

                        @if ($subasta->upcatalogo == 'S')
                            <p class="text-center">
                                <a class="btn btn-subasta"
                                    href="{{ \Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'cat') }}"
                                    title="{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_catalog') }}"
                                    target="_blank">{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_catalog') }}</a>
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        @elseif(Session::has('user'))
            <?php
            $historico = [];
            foreach ($data['auction_list'] as $value) {
                $year = date('Y', strtotime($value->session_start));

				if($year < '2025') continue;

                $historico[$year][$value->cod_sub][] = $value;
                usort($historico[$year][$value->cod_sub], function ($a, $b) {
                    return strcmp($a->reference, $b->reference);
                });
            }
            ?>
            @foreach ($historico as $key => $sub)
                <div class="col-xs-12 sub-h">
                    <div class="dat">
                        {{ $key }}
                    </div>
                </div>
                @foreach ($sub as $sessions)
                    @foreach ($sessions as $subasta)
                        @php
                            $url_lotes =
                                \Routing::translateSeo('subasta') .
                                $subasta->cod_sub .
                                '-' .
                                str_slug($subasta->name) .
                                '-' .
                                $subasta->id_auc_sessions;
                            $url_tiempo_real =
                                \Routing::translateSeo('api/subasta') .
                                $subasta->cod_sub .
                                '-' .
                                str_slug($subasta->name) .
                                '-' .
                                $subasta->id_auc_sessions;
                            $url_subasta =
                                \Routing::translateSeo('info-subasta') .
                                $subasta->cod_sub .
                                '-' .
                                str_slug($subasta->name);
                        @endphp
                        <div class="col-xs-12 col-sm-4 col-lg-3">
                            <div class="item_subasta">
                                <div class="date-sub-content">
                                    @if ($subasta->session_start)
                                        <?php
                                        $fecha = strftime('%d/%m/%Y', strtotime($subasta->session_start));

                                        ?>
                                        <div class="date-sub">{{ $fecha }}</div>
                                    @endif
                                </div>
                                <a href="{{ $url_lotes }}" title="{{ $subasta->name }}">
                                    <div class="img-lot">
                                        <img class="img-responsive"
                                            src="{{ Tools::url_img_session('subasta_medium', $subasta->cod_sub, $subasta->reference) }}"
                                            alt="{{ $subasta->name }}" />
                                    </div>
                                </a>
                                <div class="item_subasta_item text-center">
                                    {{ $subasta->name }}
                                </div>

                                <p>
                                    <a class="btn btn-subasta" href="{{ $url_subasta }}"
                                        title="{{ $subasta->name }}">
                                        @if ($subasta->tipo_sub == 'V')
                                            {{ trans(\Config::get('app.theme') . '-app.subastas.see_venta_directa') }}
                                        @else
                                            {{ trans(\Config::get('app.theme') . '-app.subastas.see_subasta') }}
                                        @endif
                                    </a>
                                </p>

                                <p><a class=" btn btn-lotes btn-color" href="{{ $url_lotes }}"
                                        title="{{ $subasta->name }}">{{ trans(\Config::get('app.theme') . '-app.subastas.see_lotes') }}</a>
                                </p>

                                @if ($subasta->uppreciorealizado == 'S')
                                    <p class="text-center">
                                        <a class="btn btn-subasta"
                                            href="{{ \Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'pre') }}"
                                            title="{{ trans(\Config::get('app.theme') . '-app.grid.pdf_adj') }}"
                                            target="_blank">{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_adj') }}</a>
                                        <br>
                                    </p>
                                @endif

                                @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time())
                                    <p class="text-center">
                                        <a class="btn btn-block btn-live" href="{{ $url_tiempo_real }}"
                                            title="{{ trans(\Config::get('app.theme') . '-app.header.from') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_start), 'd/m/Y H:i') }} {{ trans(\Config::get('app.theme') . '-app.header.to') }} {{ date_format(date_create_from_format('Y-m-d H:i:s', $subasta->session_end), 'd/m/Y H:i') }}"
                                            target="_blank">Puja en vivo</a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            @endforeach

			@php
				$empToHistoric = Config::get('app.main_emp') == '006' ? ['006'] : ['002', '005'];
				$toDate = '2025-01-01';
				$staticAuctions = (new App\Models\Subasta())->getStaticHistoricAuctionsWithoutEmp($empToHistoric, $toDate);
				$staticAuctionsForYear = $staticAuctions->groupBy(function ($item) {
					return date('Y', strtotime($item->start));
				});
			@endphp

			@foreach ($staticAuctionsForYear as $year => $auctions)
			<div class="col-xs-12 sub-h">
				<div class="dat">
					{{ $year }}
				</div>
			</div>

				@foreach ($auctions as $auction)
				<div class="col-xs-12 col-sm-4 col-lg-3">
					<div class="item_subasta">
						<div class="date-sub-content">
							<div class="date-sub">{{ strftime('%d/%m/%Y', strtotime($auction->start)) }}</div>
						</div>

						<a title="{{ $auction->name }}">
							<div class="img-lot">
								<img class="img-responsive"
									src="{{ "/img/thumbs/263/SESSION_{$auction->company}_{$auction->auction}_{$auction->reference}.jpg" }}"
									alt="{{ $auction->name }}"
									loading="lazy"
									 />
							</div>
						</a>
						<div class="item_subasta_item text-center">
							{{ $auction->name }}
						</div>

						@if($auction->uppreciorealizado == 'S')
						<p class="text-center">
							<a class="btn btn-subasta"
								href="{{ "/files/{$auction->company}_{$auction->auction}_{$auction->reference}_pre_es.pdf" }}"
								title="{{ trans(\Config::get('app.theme') . '-app.grid.pdf_adj') }}"
								target="_blank">
								{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_adj') }}
							</a>
						</p>
						@endif

						@if($auction->upcatalogo == 'S')
						<p class="text-center">
							<a class="btn btn-subasta"
								href="{{ "/files/{$auction->company}_{$auction->auction}_{$auction->reference}_cat_es.pdf" }}"
								title="{{ trans(\Config::get('app.theme') . '-app.grid.pdf_adj') }}"
								target="_blank">
								{{ trans("$theme-app.subastas.pdf_catalog") }}
							</a>
						</p>
						@endif

					</div>
				</div>
				@endforeach

			@endforeach

            {{-- @include('content.subastas_historicas') --}}
        @else
            <div class=" col-lg-12">
                <h1 class="tit text-center"> {{ trans(\Config::get('app.theme') . '-app.subastas.not-register') }}</h1>
            </div>
        @endif
    </div>
</div>
