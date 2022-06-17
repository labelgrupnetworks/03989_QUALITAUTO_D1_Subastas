<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 titlePage">
            @if (request('finished') == 'true')
                <h1> {{ trans(\Config::get('app.theme') . '-app.subastas.price_made_long') }}</h1>
            @else
                <h1> {{ $data['name'] }}</h1>
            @endif
			<p class="mini-underline"></p>
        </div>
        <?php
        if (!empty($_GET['finished'])) {
            foreach ($data['auction_list'] as $key => $sub_finished) {
                if (strtotime($sub_finished->session_end) <= time() && $_GET['finished'] == 'false') {
                    unset($data['auction_list'][$key]);
                } elseif (strtotime($sub_finished->session_end) > time() && $_GET['finished'] == 'true') {
                    unset($data['auction_list'][$key]);
                    krsort($data['auction_list']);
                }
            }
        }

        ?>
        @if ($data['subc_sub'] != 'H')
            @foreach ($data['auction_list'] as $subasta)
                <?php
                $indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
                if (count($indices) > 0) {
                    $url_lotes = \Routing::translateSeo('indice-subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
                } else {
                    $url_lotes = \Routing::translateSeo('subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
                }
                $url_tiempo_real = \Routing::translateSeo('api/subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
                $url_subasta = \Routing::translateSeo('info-subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name);

                $url_lotes_no_vendidos = \Routing::translateSeo('subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions . '?no_award=1';

                // Se obtiene la descripción de la subasta
                $inf_subasta = new \App\Models\Subasta();
                $inf_subasta->cod = $subasta->cod_sub;
                $inf_subasta->id_auc_sessions = $subasta->id_auc_sessions;
                $ficha_subasta = $inf_subasta->getInfSubasta();


                ?>
                <div class="col-xs-12">
                    <div class="item_subasta row">

						<div class="col-xs-12 col-sm-6 col-md-4">
							<a title="{{ $subasta->name }}" href="<?= $url_lotes ?>">
                                <div>
                                    <img src="/img/load/subasta_medium/SESSION_{{ $subasta->file_code }}.jpg"
                                        alt="{{ $subasta->name }}" class="img-responsive" />
                                </div>
							</a>
						</div>
                        <div class="col-xs-12 col-sm-6 col-md-5">
                            <div class="item_subasta_item">
                                {{ $subasta->name }}
                            </div>

                            <div class="item_subasta_item_text">
                                {!! $ficha_subasta->descdet_sub !!}
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-3">
                            {{-- <p>
                                <a title="{{ $subasta->name }}" href="{{ $url_subasta }}" class="btn btn-subasta">
                                    @if ($subasta->tipo_sub == 'V')
                                        {{ trans(\Config::get('app.theme') . '-app.subastas.see_venta_directa') }}
                                    @else
                                        {{ trans(\Config::get('app.theme') . '-app.subastas.see_subasta') }}
                                    @endif
                                </a>
                            </p> --}}

                            <p><a title="{{ $subasta->name }}" href="{{ $url_lotes }}"
                                    class=" btn btn-lotes btn-3">{{ trans(\Config::get('app.theme') . '-app.subastas.see_lotes') }}</a>
                            </p>

                            <p>
                                @if (!empty(request('finished') && filter_var(request('finished'), FILTER_VALIDATE_BOOLEAN)))
                                    <a title="{{ $subasta->name }}" href="{{ $url_lotes_no_vendidos }}"
                                        class=" btn {{-- btn-lotes btn-color --}} btn-3">{{ trans(\Config::get('app.theme') . '-app.subastas.lotes_no_vendido') }}</a>
                                @endif
                            </p>

                            @if ($subasta->tipo_sub == 'W' && strtotime($subasta->session_end) > time() && $subasta->subastatr_sub == 'S')
                                <p class="text-center" style="">
                                    <a class="btn {{-- btn-block btn-live --}} btn-2" style="" href="{{ $url_tiempo_real }}"
                                        target="_blank">{{ trans("$theme-app.subastas.bid_live") }}</a>
                                </p>
                            @endif


                            @if ($subasta->uppreciorealizado == 'S')
                                <p class="text-center">
                                    <a class="btn {{-- btn-subasta --}} btn-2"
                                        title="{{ trans(\Config::get('app.theme') . '-app.grid.pdf_adj') }}"
                                        target="_blank"
                                        href="{{ \Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'pre') }}">{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_adj') }}</a>
                                </p>
                            @endif

                            @if ($subasta->upcatalogo == 'S')
                                <p class="text-center">
                                    <a class="btn {{-- btn-subasta --}} btn-3"
                                        title="{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_catalog') }}"
                                        target="_blank"
                                        href="{{ \Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'cat') }}">{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_catalog') }}</a>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @elseif(Session::has('user'))
            <?php
            $historico = [];
            foreach ($data['auction_list'] as $value) {
                $year = date('Y', strtotime($value->session_start));

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
                        <?php

                        $indices = App\Models\Amedida::indice($subasta->cod_sub, $subasta->id_auc_sessions);
                        if (count($indices) > 0) {
                            $url_lotes = \Routing::translateSeo('indice-subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
                        } else {
                            $url_lotes = \Routing::translateSeo('subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
                        }
                        $url_tiempo_real = \Routing::translateSeo('api/subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name) . '-' . $subasta->id_auc_sessions;
                        $url_subasta = \Routing::translateSeo('info-subasta') . $subasta->cod_sub . '-' . str_slug($subasta->name);

                        ?>
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
                                <a title="{{ $subasta->name }}" href="<?= $url_lotes ?>">
                                    <div class="img-lot">
                                        <img src="/img/load/subasta_medium/SESSION_{{ $subasta->file_code }}.jpg"
                                            alt="{{ $subasta->name }}" class="img-responsive" />
                                    </div>
                                </a>
                                <div class="item_subasta_item text-center">
                                    {{ $subasta->name }}
                                </div>

                                <p><a title="{{ $subasta->name }}" href="{{ $url_subasta }}"
                                        class="btn btn-subasta">{{ trans(\Config::get('app.theme') . '-app.subastas.see_subasta') }}</a>
                                </p>

                                <p><a title="{{ $subasta->name }}" href="{{ $url_lotes }}"
                                        class=" btn btn-lotes btn-color">{{ trans(\Config::get('app.theme') . '-app.subastas.see_lotes') }}</a>
                                </p>

                                @if ($subasta->upcatalogo == 'S')
                                    <p class="text-center">
                                        <a class="btn btn-subasta"
                                            title="{{ trans(\Config::get('app.theme') . '-app.grid.pdf_catalog') }}"
                                            target="_blank"
                                            href="{{ \Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'cat') }}">{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_catalog') }}</a>
                                        <br>
                                    </p>
                                @endif

                                @if ($subasta->uppreciorealizado == 'S')
                                    <p class="text-center">
                                        <a class="btn btn-subasta"
                                            title="{{ trans(\Config::get('app.theme') . '-app.grid.pdf_adj') }}"
                                            target="_blank"
                                            href="{{ \Tools::url_pdf($subasta->cod_sub, $subasta->reference, 'pre') }}">{{ trans(\Config::get('app.theme') . '-app.subastas.pdf_adj') }}</a>
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

        @else
            <div class=" col-lg-12">
                <h1 class="tit text-center"> {{ trans(\Config::get('app.theme') . '-app.subastas.not-register') }}</h1>
            </div>
        @endif
    </div>
</div>
