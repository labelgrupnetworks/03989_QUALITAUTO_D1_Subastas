@if (empty($lots))
    <br><br>
    <center><big><big>{{ trans($theme . '-app.lot_list.no_results') }}</big></big></center>
@else
    @foreach ($lots as $item)
        @php

            #transformo el array en variables para conservar los nombres antiguos
            # si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller, o si solo es para este cleinte ponerlas aquí.

            foreach ($item->bladeVars as $key => $value) {
                ${$key} = $value;
            }

			$titulo =  strip_tags($item->descweb_hces1);
			$codeScrollBack = '';

            if (empty(\Config::get('app.paginacion_grid_lotes'))) {
                $idlot = 'lot_' . $item->sub_asigl0 . '_' . $item->ref_asigl0;
                $codeScrollBack = " id=\"$idlot\" onclick=\"changeURL('$actualPage', '$idlot')\" ";
            }
        @endphp

        @include('includes.grid.lot')
    @endforeach
@endif
