@php
    $ficha_subasta = $data['auction'];
@endphp

<div class="container mb-1">
    <div class="row">
        <div class="col-xs-12">
            <div class="grid-title-wrapper">
                <h1 class="grid-title">
                    {{ trans("$theme-app.subastas.inf_subasta_subasta") }} {{ $ficha_subasta->des_sub }}
                </h1>
                <div class="next">
                    @if ($data['previous'])
                        <a class="nextLeft" title="{{ trans("$theme-app.subastas.previous_auction") }}"
                            href="{{ $data['previous'] }}">
                            <i class="fa fa-angle-left fa-angle-custom"></i>
                            {{ trans("$theme-app.subastas.previous_auction") }}
                        </a>
                    @endif
                    @if ($data['next'])
                        <a class="nextRight" title="{{ trans("$theme-app.subastas.next_auction") }}"
                            href="{{ $data['next'] }}">
                            {{ trans("$theme-app.subastas.next_auction") }}
                            <i class="fa fa-angle-right fa-angle-custom"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="auction-cover">
        <div class="auction-cover-image">
            <img width="100%"
                src="/img/load/subasta_large/SESSION_{{ $ficha_subasta->emp_sub }}_{{ $ficha_subasta->cod_sub }}_{{ $ficha_subasta->reference }}.jpg"
                class="img-responsive">
        </div>
        <div class="auction-cover-info">
            <div class="session-desc">
                <p>{!! $ficha_subasta->session_info ?? ($ficha_subasta->descdet_sub ?? '') !!}</p>
            </div>
        </div>
        <div class="auction-cover-links">

            <div class="links-auction">
                <h5 class="text-uppercase bold mb-1">{{ trans("$theme-app.subastas.sessions") }}</h5>

                <ul class="list-content">
                    @foreach ($data['sessions'] as $session)
                        <li class="link-auction">
                            <a title="Ver lotes"
                                href="{{ Tools::url_indice_auction($session->auction, $session->name, $session->id_auc_sessions) }}">

                                {{ $session->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if ($ficha_subasta->tipo_sub == 'W' && strtotime($ficha_subasta->end) > time())
                <a class="btn btn-block btn-color"
                    href="{{ Routing::translateSeo('api/subasta') . $ficha_subasta->cod_sub . '-' . str_slug($ficha_subasta->name) . '-' . $ficha_subasta->id_auc_sessions }}"
                    target="_blank">{{ trans("$theme-app.subastas.lot_subasta_online") }}
                </a>
            @endif

            @if ($ficha_subasta->upcatalogo == 'S')
                <a class="btn btn-block btn-subasta" title="{{ trans("$theme-app.subastas.pdf_catalog") }}"
                    target="_blank"
                    href="{{ \Tools::url_pdf($ficha_subasta->cod_sub, $ficha_subasta->reference, 'cat') }}">
                    {{ trans("$theme-app.subastas.pdf_catalog") }}
                </a>
            @endif
            <a class="btn btn-block btn-subasta" title="{{ trans("$theme-app.foot.term_condition") }}" target="_blank"
                href="{{ Routing::translateSeo('pagina') . trans("$theme-app.links.term_condition") }}">
                {{ trans("$theme-app.foot.term_condition") }}
            </a>

            <div class="share-panel-auction">
                @include('includes.share_list', [
                    'url' => url()->full(),
                    'text' => $ficha_subasta->des_sub,
                ])
            </div>

        </div>
    </div>

</div>
