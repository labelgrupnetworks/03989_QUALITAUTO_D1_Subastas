@if (empty($lots))
    <h3 class="text-center">{{ trans($theme . '-app.lot_list.no_results') }}</h3>
@else
    @foreach ($lots as $item)
        <?php
        #transformo el array en variables para conservar los nombres antiguos
        # si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller, o si solo es para este cleinte ponerlas aquí.

        foreach ($item->bladeVars as $key => $value) {
            ${$key} = $value;
        }

        $class_square = 'col-xs-12 col-sm-6 col-lg-4';

        if (empty(\Config::get('app.paginacion_grid_lotes'))) {
            $idlot = 'lot_' . $item->sub_asigl0 . '_' . $item->ref_asigl0;
            $codeScrollBack = " id=\"$idlot\" onclick=\"changeURL('$actualPage', '$idlot')\" ";
        } else {
            $codeScrollBack = '';
        }

        $titulo = trans($theme . '-app.lot.lot-name') . ' ' . $item->ref_asigl0;
        $caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($item->num_hces1, $item->lin_hces1);

        ?>
        @include('includes.grid.lot')
    @endforeach
@endif
