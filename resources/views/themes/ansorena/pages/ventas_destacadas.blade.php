@extends('layouts.default')
@php

$key = 'ventas_destacadas';
$replace = [
    'lang' => \Tools::getLanguageComplete(Config::get('app.locale')),
    'emp' => Config::get('app.emp'),
];
$bloque = new App\Models\Bloques();
$lots = $bloque->getResultBlockByKeyname($key, $replace);

@endphp
@section('content')

@if (empty($lots))
    <br><br>
    <center><big><big>{{ trans(\Config::get('app.theme') . '-app.lot_list.no_results') }}</big></big></center>
@else
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 text-center color-letter">
			@php

					$pagina = new App\Models\Page();
					$menuEstaticoHtml  = $pagina->getPagina(Str::upper(\Config::get("app.locale")),"MENUSUBASTAS");


			@endphp
			{!! $menuEstaticoHtml->content_web_page!!}
		</div>
	</div>
</div>
    <div class="container">
        <div class="row ventas-destacadas-container">
			<div class="text-center mt-1">
				<h1 class="titlePage">{{ trans("$theme-app.lot_list.featured-sales") }}</h1>
			</div>
			<div class="mt-3">
            @foreach ($lots as $item)

                <?php
                #transformo el array en variables para conservar los nombres antiguos
                # si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller, o si solo es para este cleinte ponerlas aquí.
                /* dd($item); */
                /* foreach ($item->bladeVars as $key => $value) {
                                    ${$key} = $value;
                                } */

                $class_square = 'col-xs-12 col-sm-6 col-lg-4';

                if (empty(\Config::get('app.paginacion_grid_lotes'))) {
                    $idlot = 'lot_' . $item->sub_asigl0 . '_' . $item->ref_asigl0;
                    $codeScrollBack = " id=\"$idlot\" onclick=\"changeURL('$actualPage', '$idlot')\" ";
                } else {
                    $codeScrollBack = '';
                }

                $titulo = trans(\Config::get('app.theme') . '-app.lot.lot-name') . ' ' . $item->ref_asigl0;
                $caracteristicas = App\Models\V5\FgCaracteristicas_Hces1::getByLot($item->num_hces1, $item->lin_hces1);

                ?>

                @include('includes.grid.lot_venta_destacada')
            @endforeach
			</div>
        </div>
    </div>
@endif
@stop
