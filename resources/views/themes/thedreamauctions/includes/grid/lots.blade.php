@if (empty($lots))
	<br><br>
	<center><big><big>{{ trans(\Config::get('app.theme').'-app.lot_list.no_results') }}</big></big></center>
@else

	@php
		$refs = $lots->pluck('ref_asigl0');
		$preciosEstimadosAltos = \App\Models\V5\FgAsigl0::select('ref_asigl0', 'imptash_asigl0')->where('sub_asigl0', $lots[0]->sub_asigl0)->whereIn('ref_asigl0', $refs)->pluck('imptash_asigl0', 'ref_asigl0');
	@endphp

	@foreach ($lots as  $item)
		<?php
				#transformo el array en variables para conservar los nombres antiguos
				# si es necesario ampliar varibles se puede hacer en la funcion setVarsLot del lotlistcontroller, o si solo es para este cleinte ponerlas aquí.

			foreach($item->bladeVars as $key => $value){
				${$key} = $value;
			}
				$class_square = 'col-xs-12 col-sm-6 col-lg-4';

				$estimacionAlta = $preciosEstimadosAltos[$item->ref_asigl0];


			if(empty(\Config::get("app.paginacion_grid_lotes"))){
				$idlot="lot_".$item->sub_asigl0."_".$item->ref_asigl0;
				$codeScrollBack =" id=\"$idlot\" onclick=\"changeURL('$actualPage', '$idlot')\" ";
			}else{
				$codeScrollBack ="";
			}
		?>
		@include('includes.grid.lot')
	@endforeach
@endif
