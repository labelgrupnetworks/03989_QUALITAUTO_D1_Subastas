@php
    use App\Models\V5\AucSessions;

    $searchUrl = Tools::url_lot_to_js(
        $lote_actual->cod_sub,
        $lote_actual->id_auc_sessions,
        $lote_actual->ref_asigl0,
        $lote_actual->num_hces1,
    );
    $sessions = AucSessions::query()
        ->select(['"id_auc_sessions"', '"auction"', '"name_lang"', '"name"', '"init_lot"'])
        ->joinLang()
        ->whereAuction($lote_actual->cod_sub)
        ->orderby('"reference"')
        ->get();

@endphp

<div class="top-filters top-filters-ficha">
    <div class="container container-top-filters" data-container-style>
        <div class="filters-sessions d-flex align-items-center">

            @foreach ($sessions as $sesion)
                @php
                    $sesionName = explode('-', $sesion->name);
                    $sesionName = !empty($sesionName[1]) ? trim($sesionName[1]) : $sesionName[0];
					$sesionName = Str::title($sesionName);

                    $urlSession = Tools::url_auction($sesion->auction, $sesion->name, $sesion->id_auc_sessions, '001');
                    #poner esto antes de la página a la que debe ir
                    if (empty($url)) {
                        $url = $urlSession;
                    }

                    #calculamos en que página empieza la sesion
                    $cuantosLotes = App\Models\V5\FgAsigl0::select('count(ref_asigl0) cuantos')
                        ->where('SUB_ASIGL0', $lote_actual->cod_sub)
                        ->where('ref_asigl0', '<', $sesion->init_lot)
                        ->first();

                    $lotsPerPage = request('total', 24);

                    $pagina = intdiv($cuantosLotes->cuantos, $lotsPerPage);

                    #le sumamos 1 por que la página no empieza em 0 si no en 1
                    $pagina += 1;

                    $urlSession .= "?page={$pagina}&total={$lotsPerPage}#{$lote_actual->cod_sub}-{$sesion->init_lot}";
                @endphp

                <div @class([
					'filters-session d-none d-lg-block',
					'ps-0' => $loop->first,
					'pe-0' => $loop->last,
				])>
                    <a href="{{ $urlSession }}">{{ $sesionName }}</a>
                </div>
            @endforeach
        </div>


        <div class="filters-orders ms-auto">
            <form class="form-single-lot col-12 row align-items-center gx-1" id="searchLot" method="get"
                action="{{ $searchUrl }}">
                <label class="w-auto" for="reference">
                    {{ trans("$theme-app.lot_list.reference") }}
                </label>
                <div class="col">
                    <input class="form-control form-control-sm filter-auction-input search-input_js" id="single-lot"
                        name="reference" type="text" value="" placeholder="1364">
                </div>
                <button class="btn btn-sm btn-link w-auto" type="submit">
                    <x-icon.fontawesome icon="magnifying-glass" />
                </button>
            </form>
        </div>
    </div>
</div>
