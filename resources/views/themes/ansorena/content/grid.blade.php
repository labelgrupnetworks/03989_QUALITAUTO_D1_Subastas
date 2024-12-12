@php
    if (!$auction) {
        return;
    }

    use Carbon\Carbon;
    use App\Models\V5\AucSessionsFiles;

    $completeLocale = Tools::getLanguageComplete(\Config::get('app.locale'));
    $localeToTime = str_replace('-', '_', $completeLocale);
    $dateFormat = $localeToTime === 'es_ES' ? 'D [de] MMMM' : 'MMMM Do';

    $emp = Config::get('app.emp');
    $isPresencial = $auction->tipo_sub == 'W';

    $sessions = App\Models\V5\AucSessions::select(
        '"id_auc_sessions","auction","reference", nvl("name_lang","name") name, "start", "end", "init_lot", "end_lot"',
    )
        ->leftjoin(
            '"auc_sessions_lang"',
            '"id_auc_session_lang" = "id_auc_sessions" and "lang_auc_sessions_lang" =\'' . $completeLocale . '\'',
        )
        ->where('"auction"', $auction->cod_sub)
        ->orderby('"reference"')
        ->get();

    $liveSession = null;
    if ($isPresencial) {
        $liveSession = $sessions->where('end', '>', now())->first();
    }

    $auctionImage = Tools::urlAssetsCache("/img/AUCTION_{$emp}_{$auction->cod_sub}.jpg");

    $catalogUrl = "/catalogos/{$auction->cod_sub}";
    if ($auction->tipo_sub == 'O') {
        $auctionFile = AucSessionsFiles::where([['"auction"', $auction->cod_sub], ['"type"', 1]])->first();

        if ($auctionFile) {
            $catalogUrl = $auctionFile->publicFilePath;
        }
    }

    $auctionTitle = trans("$theme-app.subastas.inf_subasta_subasta") . ' ' . $auction->cod_sub;

    $isOnlyPurchasables = request('purchasable') == true;
    if ($isOnlyPurchasables) {
        $filters['purchasable'] = true;
        $auctionImage = '/themes/ansorena/assets/img/tmp/grid_joyas.jpg';
        $auctionTitle = 'XMAS GIFTS';
        $sessions = [];
        $catalogUrl = '';
    }

@endphp

<main @class(['grid-page pt-2', 'grid-xmas-page' => $isOnlyPurchasables])>
    <h1 class="ff-highlight grid-page-tile">{{ $auctionTitle }}</h1>

    @if ($isOnlyPurchasables)
        <h2 class="grid-page-subtitle h4 text-center">
            {{ trans("$theme-app.lot_list.alternative_auction_subtitle") }}
        </h2>
    @endif

    <section class="grid-front-page" style="background-image: url({{ $auctionImage }})">
        <div class="btn-group btn-group-grid @if ($liveSession) grid-2 @endif">
            <a class="btn btn-auction" href="#grid-lots"
                aria-current="page">{{ trans("$theme-app.subastas.see_lotes") }}</a>

            @if ($liveSession)
                <a class="btn btn-auction"
                    href="{{ Tools::url_real_time_auction($liveSession->auction, $liveSession->name, $liveSession->id_auc_sessions) }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="12" fill="#ED2F2F" />
                    </svg>
                    <p>{{ trans("$theme-app.lot_list.bid_live") }}</p>
                </a>
            @endif
        </div>
    </section>

    @if ($isPresencial)
        <section class="container grid-session">
            <div class="row row-cols-1 row-cols-md-3 g-4">

                @foreach ($sessions as $sesion)
                    @php
                        $fecha = Carbon::parse($sesion->start);
                        $hour = date('H:i', strtotime($sesion->start));
                        $urlSession = \Tools::url_auction(
                            $sesion->auction,
                            $sesion->name,
                            $sesion->id_auc_sessions,
                            '001',
                        );
                        #poner esto antes de la página a la que debe ir
                        if (empty($url)) {
                            $url = $urlSession;
                        }
                        #calculamos en que página empieza la sesion
                        $cuantosLotes = App\Models\V5\FgAsigl0::select('count(ref_asigl0) cuantos')
                            ->where('SUB_ASIGL0', $auction->cod_sub)
                            ->where('ref_asigl0', '<', $sesion->init_lot)
                            ->first();
                        #por defecto 24 como en lotlistcontroller
                        $lotsPerPage = request('total');
                        if (empty($lotsPerPage)) {
                            $lotsPerPage = 24;
                        }
                        $pagina = intdiv($cuantosLotes->cuantos, $lotsPerPage);
                        #le sumamos 1 por que la página no empieza em 0 si no en 1
                        $pagina += 1;
                        $urlSession .=
                            "?page=$pagina" .
                            '&total=' .
                            request('total', 24) .
                            '#' .
                            $auction->cod_sub .
                            '-' .
                            $sesion->init_lot;
                    @endphp

                    <div class="col">
                        <div class="session position-relative">
                            <p>
                                <span
                                    class="opacity-50">{{ trans("$theme-app.lot_list.session") . ' ' . intval($sesion->reference) }}</span><br>
                                {{ $fecha->locale($localeToTime)->isoFormat($dateFormat) }}
                            </p>
                            <p class="ff-highlight session-name">{{ $sesion->name }}</p>
                            <p>
                                {{ trans("$theme-app.lot_list.from_to_lot", [
                                    'init_lot' => $sesion->init_lot,
                                    'end_lot' => $sesion->end_lot,
                                ]) }}
                                <br>
                                {{ trans("$theme-app.home.home") . " $hour" . 'H' }}
                            </p>

                            <a class="stretched-link" href="{{ $urlSession }}"></a>
                        </div>
                    </div>
                @endforeach

            </div>
        </section>
    @endif

    <section class="grid-section">

        <div class="grid-section-header" id="grid-lots">
            <h2 class="ff-highlight grid-section-title">{{ trans("$theme-app.lot_list.lots") }}</h2>
            @if (!empty($catalogUrl))
                <a href="{{ $catalogUrl }}" target="_blank">{{ trans("$theme-app.lot_list.ver_catalogo") }}</a>
            @endif
        </div>

        <div class="grid-lots position-relative">

            <aside class="lots-filters">
                <div class="lots-filters-content">
                    <button class="btn-close" type="button" aria-label="Close"></button>
                    @include('includes.grid.leftFilters')
                </div>
            </aside>

            <div class="container">

                @include('includes.grid.topFilters')

                @if (Config::get('app.paginacion_grid_lotes'))
                    <div class="section-grid-lots row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 gy-4">
                        @include('includes.grid.lots')
                    </div>

                    <div class="pagination-wrapper">
                        {{ $paginator->fragment('grid-lots')->links('front::includes.grid.paginator_pers') }}
                    </div>
                @endif

            </div>
        </div>

    </section>

</main>

@if (!isset($auction) && (!isset($_GET['page']) || $_GET['page'] == 1))
    <div class="home_text">
        <div class="container">
            {!! $seo_data->meta_content !!}
            <?php
            #Solo debe aparecer si hay categioria, en el moment oque ha seccion seleccionada no debe aparecer
            ?>
            @if (empty($filters['section']))
                <div class="links-sections">
                    @foreach ($sections as $sec)
                        <a class="mr-2"
                            href="{{ route('section', ['keycategory' => $infoOrtsec->key_ortsec0, 'keysection' => $sec['key_sec']]) }}">{{ ucfirst($sec['des_sec']) }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

@endif

<script>
    var url_lots = "{{ route('getAjaxLots', ['lang' => \Config::get('app.locale')]) }}";
</script>

@if (empty(\Config::get('app.paginacion_grid_lotes')))
    <script src="{{ Tools::urlAssetsCache('/js/default/grid_scroll.js') }}"></script>
@endif
<script src="{{ Tools::urlAssetsCache('/js/default/grid_filters.js') }}"></script>
