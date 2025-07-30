@php
    use App\Services\Auction\AuctionService;
@endphp

@if (empty($lots))
    <h2 class="mt-5">{{ trans('web.lot_list.no_results') }}</h2>
@else
    @php
        $isLastHistoryAuction = (new AuctionService())->isLastHistoryAuction($auction->cod_sub);
    @endphp
    @foreach ($lots as $item)
        @php
            // transformo el array en variables para conservar los nombres antiguos
 			// si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller,
			// o si solo es para este cliente ponerlas aquí.
            foreach ($item->bladeVars as $key => $value) {
                ${$key} = $value;
            }

            if (empty(\Config::get('app.paginacion_grid_lotes'))) {
                $idlot = 'lot_' . $item->sub_asigl0 . '_' . $item->ref_asigl0;
                $codeScrollBack = " id=\"$idlot\" onclick=\"changeURL('$actualPage', '$idlot')\" ";
            } else {
                $codeScrollBack = '';
            }
        @endphp

        @include('includes.grid.lot')
    @endforeach
@endif
